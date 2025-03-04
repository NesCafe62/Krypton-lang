<?php

class Parser {

	/** @var Token[] */
	protected $tokens;

	/** @var int */
	protected $tokensCount;

	/** @var int */
	protected $current = 0;

	/** @var array */
	protected $keywordNames;

	/** @var CompilerExtension[] */
	protected $extensions;

	/** @var CompilerExtension[] */
	protected $extensionsRoot;

	/** @var bool */
	protected $hasExtensions;

	/** @var string */
	protected $fileName;

	public function __construct() {
		$this->keywordNames = [];
		foreach (Lang::$keywords as $keyword => $keywordId) {
			$this->keywordNames[$keywordId] = $keyword;
		}
	}

	protected function consume(int $count): void {
		$this->current += $count;
	}

	protected function peekUnchecked(int $offset): Token {
		return $this->tokens[$this->current + $offset];
	}

	protected function peek(int $offset): ?Token {
		$index = $this->current + $offset;
		if ($index >= $this->tokensCount) {
			return null;
		}
		return $this->tokens[$index];
	}

	protected function remainingCount(): int {
		return $this->tokensCount - 1 - $this->current;
	}

	protected function getLineAndCol(): array {
		$token = ($this->current < $this->tokensCount)
			? $this->tokens[$this->current]
			: $this->tokens[$this->current - 1];
		return [$token->line, $token->col];
	}

	protected function error(string $message): void {
		[$line, $col] = $this->getLineAndCol();
		throw new CompilerException("{$this->fileName}:{$line}:{$col}: {$message}");
	}

	protected function peekToken(int $offset, int $tokenType): ?Token {
		$index = $this->current + $offset;
		if ($index >= $this->tokensCount) {
			return null;
		}
		$token = $this->tokens[$index];
		return ($token->token === $tokenType) ? $token : null;
	}

	protected function peekTokenValue(int $offset, int $tokenType, string $value): ?Token {
		$index = $this->current + $offset;
		if ($index >= $this->tokensCount) {
			return null;
		}
		$token = $this->tokens[$index];
		return $token->isType($tokenType, $value) ? $token : null;
	}

	protected function tryConsumeToken(int $tokenType): ?Token {
		if ($this->current >= $this->tokensCount) {
			return null;
		}
		$token = $this->tokens[$this->current];
		if ($token->token !== $tokenType) {
			return null;
		}
		$this->consume(1);
		return $token;
	}

	protected function tryConsumeTokenValue(int $tokenType, string $value): ?Token {
		if ($this->current >= $this->tokensCount) {
			return null;
		}
		$token = $this->tokens[$this->current];
		if (!$token->isType($tokenType, $value)) {
			return null;
		}
		$this->consume(1);
		return $token;
	}

	protected function consumeTokenOrError(int $tokenType): Token {
		if ($this->current < $this->tokensCount) {
			$token = $this->tokens[$this->current];
			if ($token->token === $tokenType) {
				$this->consume(1);
				return $token;
			}
		} else {
			$token = $this->tokens[$this->current - 1];
		}
		$display = Lang::displayToken($tokenType);
		throw new CompilerException("{$this->fileName}:{$token->line}:{$token->col}: Expected {$display}");
	}

	protected function consumeTokenValueOrError(int $tokenType, string $value): Token {
		if ($this->current < $this->tokensCount) {
			$token = $this->tokens[$this->current];
			if ($token->isType($tokenType, $value)) {
				$this->consume(1);
				return $token;
			}
		} else {
			$token = $this->tokens[$this->current - 1];
		}
		$display = Lang::displayToken($tokenType) . ":`{$value}`";
		throw new CompilerException("{$this->fileName}:{$token->line}:{$token->col}: Expected {$display}");
	}


	/**
	 * @return NodeTypeExpression|null
	 */
	protected function tryConsumeTypeExpression(): ?object {
		$tokenType = $this->tryConsumeToken(TokenType::TYPE_NAME);
		if ($tokenType === null) {
			return null;
		}
		$typeExpr = $this->visitNode(
			(object) [
				'node' => NodeType::TYPE_EXPRESSION,
				'name' => Lang::$types[$tokenType->value] ?? $tokenType->value,
				'line' => $tokenType->line, 'col' => $tokenType->col,
				// todo: generics support
				// 'args' => [],
			]
		);
		if ($typeExpr === null || $typeExpr->node !== NodeType::TYPE_EXPRESSION) {
			throw new CompilerRuntimeException("Node type should be 'TypeExpression'");
		}
		return $typeExpr;
	}

	/**
	 * @return NodeIdentifier|null
	 */
	protected function tryConsumeIdentifier(): ?object {
		$tokenIdentifier = $this->tryConsumeToken(TokenType::IDENTIFIER);
		if ($tokenIdentifier === null) {
			return null;
		}
		return $this->visitNode(
			(object) [
				'node' => NodeType::IDENTIFIER,
				'name' => $tokenIdentifier->value,
				'line' => $tokenIdentifier->line, 'col' => $tokenIdentifier->col,
			]
		);
	}

	/**
	 * @return Node|NodeLiteralInt|NodeLiteralBool|null
	 */
	protected function tryConsumeLiteral(): ?object {
		$token = $this->tryConsumeToken(TokenType::INT);
		if ($token !== null) {
			return $this->visitNode(
				(object) [
					'node' => NodeType::LITERAL_INT,
					'value' => (int) $token->value, // maybe keep as string
					'line' => $token->line, 'col' => $token->col,
				]
			);
		}
		$token = $this->tryConsumeToken(TokenType::STRING);
		if ($token != null) {
			return $this->visitNode(
				(object) [
					'node' => NodeType::LITERAL_STRING,
					'value' => $token->value,
					'line' => $token->line, 'col' => $token->col,
				]
			);
		}
		$token = $this->tryConsumeToken(TokenType::KEYWORD_TRUE);
		if ($token !== null) {
			return $this->visitNode(
				(object) [
					'node' => NodeType::LITERAL_BOOL,
					'value' => 1,
					'line' => $token->line, 'col' => $token->col,
				]
			);
		}
		$token = $this->tryConsumeToken(TokenType::KEYWORD_FALSE);
		if ($token !== null) {
			return $this->visitNode(
				(object) [
					'node' => NodeType::LITERAL_BOOL,
					'value' => 0,
					'line' => $token->line, 'col' => $token->col,
				]
			);
		}
		return null;
	}

	/**
	 * @param bool $onlyAssignable
	 * @return Node|null
	 */
	protected function tryConsumeParenExpression(bool $onlyAssignable = false): ?object {
		if ($this->tryConsumeToken(TokenType::PAREN_OPEN) === null) {
			return null;
		}
		$expr = $this->tryConsumeExpression($onlyAssignable) ?? $this->error("Expected expression #1.2");
		$this->consumeTokenOrError(TokenType::PAREN_CLOSE);
		return $expr;
	}

	/**
	 * @param bool $onlyAssignable
	 * @return Node|null
	 */
	protected function tryConsumeTerm(bool $onlyAssignable = false): ?object {
		$tokenOp = $this->tryConsumeToken(TokenType::OPERATOR);
		if (
			$tokenOp !== null &&
			($tokenOp->value === '++' || $tokenOp->value === '--')
		) {
			$term = (
				$this->tryConsumeParenExpression(true) ??
				$this->tryConsumeIdentifier() ??
				$this->error("Expected identifier")
			);
			return $this->visitNode(
				(object) [
					'node' => NodeType::EXPRESSION_UPDATE,
					'operator' => $tokenOp->value,
					'value' => $term,
					'prefix' => true,
					'line' => $tokenOp->line, 'col' => $tokenOp->col,
				]
			);
		}

		$term = (
			$this->tryConsumeParenExpression($onlyAssignable) ??
			$this->tryConsumeIdentifier() ??
			($onlyAssignable ? null : $this->tryConsumeLiteral())
		);
		if ($tokenOp !== null) {
			if ($tokenOp->value !== '!' && $tokenOp->value !== '-' && $tokenOp->value !== '+' && $tokenOp->value !== '~') {
				$this->error("Undeclared unary operator '{$tokenOp->value}'");
			}
			// $exprRight = $this->tryConsumeExpression() ?? $this->error("Expected expression #2");
			if ($term === null) {
				$this->error("Expected expression #3");
			}
			return $this->visitNode(
				(object) [
					'node' => NodeType::EXPRESSION_UNARY,
					'operator' => $tokenOp->value,
					'value' => $term,
					'line' => $tokenOp->line, 'col' => $tokenOp->col,
				]
			);
		}

		$tokenOp = $this->peekToken(0, TokenType::OPERATOR);
		if (
			$tokenOp !== null &&
			($tokenOp->value === '++' || $tokenOp->value === '--')
		) {
			$this->consume(1);
			if ($term->node !== NodeType::IDENTIFIER) {
				$this->error("Unary operator '{$tokenOp->value}' can only be used with an assignable expression #1");
			}
			return $this->visitNode(
				(object) [
					'node' => NodeType::EXPRESSION_UPDATE,
					'operator' => $tokenOp->value,
					'value' => $term,
					'prefix' => false,
					'line' => $tokenOp->line, 'col' => $tokenOp->col,
				]
			);
		}

		return $term;
	}

	/**
	 * @param bool $onlyAssignable
	 * @param bool $excludeRangeOp
	 * @param int $minPrecedence
	 * @return Node|null
	 */
	protected function tryConsumeExpression(bool $onlyAssignable = false, bool $excludeRangeOp = false, int $minPrecedence = 0): ?object {
		// EXPRESSION_UNARY | EXPRESSION_BINARY | IDENTIFIER |
		// LITERAL_INT | LITERAL_STRING | LITERAL_BOOL

		$expr = (
			$this->tryConsumeAssignmentExpression() ??
			$this->tryConsumeTerm($onlyAssignable)
		);
		if ($expr !== null) {
			while (true) {
				$tokenOp = $this->peekToken(0, TokenType::OPERATOR);
				if ($tokenOp === null) {
					break;
				}
				if ($excludeRangeOp && ($tokenOp->value === '..' || $tokenOp->value === '..=')) {
					break;
				}
				if ($tokenOp->value === '++' || $tokenOp->value === '--') {
					$this->error("Unary operator '{$tokenOp->value}' can only be used with an assignable expression #2");
				}
				[$precedence, $assoc] = Lang::getOperatorInfo($tokenOp->value);
				if ($precedence < $minPrecedence) {
					break;
				}
				$this->consume(1);
				$nextPrecedence = ($assoc === Lang::ASSOC_LEFT)
					? $precedence + 1
					: $precedence;
				$exprRight = (
					$this->tryConsumeExpression(false, false, $nextPrecedence) ??
					$this->error("Expected expression #4")
				);
				if ($expr === null) {
					throw new CompilerRuntimeException("Expression operand node should not be null");
				}
				$expr = $this->visitNode(
					(object) [
						'node' => NodeType::EXPRESSION_BINARY,
						'operator' => $tokenOp->value,
						'left' => $expr,
						'right' => $exprRight,
						'line' => $expr->line, 'col' => $expr->col,
					]
				);
			}
			return $expr;
		}
		return null;
	}


	/**
	 * @return Node|null
	 */
	protected function tryConsumeStmtReturn(): ?object {
		$tokenRet = $this->tryConsumeToken(TokenType::KEYWORD_RET);
		if ($tokenRet === null) {
			return null;
		}
		$value = $this->tryConsumeExpression() ?? $this->error("Expected return value");
		$this->consumeTokenOrError(TokenType::SEMICOLON);
		return $this->visitNodeOrNoOp(
			(object) [
				'node' => NodeType::STATEMENT_RETURN,
				'value' => $value,
				'line' => $tokenRet->line, 'col' => $tokenRet->col,
			]
		);
	}

	// todo: temporary io.print
	/**
	 * @return Node|null
	 */
	protected function tryConsumeStmtIOPrint(): ?object {
		$tokenPrint = $this->tryConsumeTokenValue(TokenType::IDENTIFIER, 'io');
		if ($tokenPrint === null) {
			return null;
		}
		$this->consumeTokenValueOrError(TokenType::OPERATOR, '.');
		$this->consumeTokenValueOrError(TokenType::IDENTIFIER, 'print');
		$this->consumeTokenOrError(TokenType::PAREN_OPEN);
		$value = $this->tryConsumeExpression() ?? $this->error("Expected return value");
		$this->consumeTokenOrError(TokenType::PAREN_CLOSE);
		$this->consumeTokenOrError(TokenType::SEMICOLON);
		return $this->visitNodeOrNoOp(
			(object) [
				'node' => NodeType::STATEMENT_IO_PRINT,
				'value' => $value,
				'line' => $tokenPrint->line, 'col' => $tokenPrint->col,
			]
		);
	}

	/**
	 * @return Node[]|null
	 */
	protected function tryConsumeBlock(): ?array {
		if ($this->tryConsumeToken(TokenType::CURLY_OPEN) === null) {
			return null;
		}
		$statements = [];
		while ($this->peekToken(0, TokenType::CURLY_CLOSE) === null) {
			$statement = $this->tryConsumeStmt();
			if ($statement === null) {
				break;
			}
			$statements[] = $statement;
		}
		$this->consumeTokenOrError(TokenType::CURLY_CLOSE);
		return $statements;
	}

	/**
	 * @return NodeScope|null
	 */
	protected function tryConsumeScope(): ?object {
		$tokenCurly = $this->tryConsumeToken(TokenType::CURLY_OPEN);
		if ($tokenCurly === null) {
			return null;
		}
		$statements = [];
		while ($this->peekToken(0, TokenType::CURLY_CLOSE) === null) {
			$statement = $this->tryConsumeStmt();
			if ($statement === null) {
				break;
			}
			$statements[] = $statement;
		}
		$this->consumeTokenOrError(TokenType::CURLY_CLOSE);
		return $this->visitNodeOrNoOp(
			(object) [
				'node' => NodeType::STATEMENT_SCOPE,
				'statements' => $statements,
				'line' => $tokenCurly->line, 'col' => $tokenCurly->col,
			]
		);
	}

	/**
	 * @param bool $isElse
	 * @return Node|null
	 */
	protected function tryConsumeStmtIf(bool $isElse): ?object {
		$tokenIf = $this->tryConsumeToken(TokenType::KEYWORD_IF);
		if ($tokenIf === null) {
			return null;
		}
		$this->consumeTokenOrError(TokenType::PAREN_OPEN);
		$condition = $this->tryConsumeExpression() ?? $this->error("Expected condition");
		$this->consumeTokenOrError(TokenType::PAREN_CLOSE);
		$then = (
			$this->tryConsumeScope() ??
			$this->tryConsumeStmt() ??
			$this->error("Expected then body")
		);

		$else = null;
		if ($this->tryConsumeToken(TokenType::KEYWORD_ELSE)) {
			$else = (
				$this->tryConsumeScope() ??
				$this->tryConsumeStmt(true) ??
				$this->error("Expected else body")
			);
			if ($else->node === NodeType::STATEMENT_NOOP) {
				$else = null;
			}
		}

		return $this->visitNodeOrNoOp(
			(object) [
				'node' => $isElse ? NodeType::STATEMENT_ELSE_IF : NodeType::STATEMENT_IF,
				'condition' => $condition,
				'then' => $then,
				'else' => $else,
				'line' => $tokenIf->line, 'col' => $tokenIf->col,
			]
		);
	}

	/**
	 * @return Node|null
	 */
	protected function tryConsumeStmtWhileLoop(): ?object {
		$tokenWhile = $this->tryConsumeToken(TokenType::KEYWORD_WHILE);
		if ($tokenWhile === null) {
			return null;
		}
		$this->consumeTokenOrError(TokenType::PAREN_OPEN);
		$condition = $this->tryConsumeExpression() ?? $this->error("Expected condition");
		$this->consumeTokenOrError(TokenType::PAREN_CLOSE);
		$statements = (
			$this->tryConsumeBlock() ??
			[$this->tryConsumeStmt() ?? $this->error("Expected while body")]
		);
		return $this->visitNodeOrNoOp(
			(object) [
				'node' => NodeType::STATEMENT_WHILE_LOOP,
				'condition' => $condition,
				'statements' => $statements,
				'line' => $tokenWhile->line, 'col' => $tokenWhile->col,
			]
		);
	}

	/**
	 * @return Node|null
	 */
	protected function tryConsumeStmtForLoop(): ?object {
		$tokenFor = $this->tryConsumeToken(TokenType::KEYWORD_FOR);
		if ($tokenFor === null) {
			return null;
		}
		$this->consumeTokenOrError(TokenType::PAREN_OPEN);
		$identifier = (
			$this->tryConsumeIdentifier() ??
			$this->tryConsumeDeclVariable() ??
			$this->error("Expected condition")
		);
		if (
			$identifier->node !== NodeType::IDENTIFIER &&
			$identifier->node !== NodeType::DECLARATION_VARIABLE
		) {
			throw new CompilerRuntimeException("Node type should be 'Identifier' or 'VariableDeclaration'");
		}
		$this->consumeTokenOrError(TokenType::KEYWORD_IN);
		$from = $this->tryConsumeExpression(false, true) ?? $this->error("Expected range from value");
		if ($this->tryConsumeTokenValue(TokenType::OPERATOR, '..')) {
			$exclusive = true;
		} else if ($this->tryConsumeTokenValue(TokenType::OPERATOR, '..=')) {
			$exclusive = false;
		} else {
			$this->error("Expected range operator `..` or `..=`"); // will throw
			return null;
		}
		$to = $this->tryConsumeExpression() ?? $this->error("Expected range to value");
		$this->consumeTokenOrError(TokenType::PAREN_CLOSE);
		$statements = (
			$this->tryConsumeBlock() ??
			[$this->tryConsumeStmt() ?? $this->error("Expected while body")]
		);
		return $this->visitNodeOrNoOp(
			(object) [
				'node' => NodeType::STATEMENT_FOR_LOOP,
				'identifier' => $identifier,
				'exclusive' => $exclusive,
				'from' => $from,
				'to' => $to,
				'statements' => $statements,
				'line' => $tokenFor->line, 'col' => $tokenFor->col,
			]
		);
	}

	/**
	 * @return NodeDeclVariable|null
	 */
	protected function tryConsumeDeclVariable(): ?object {
		$firstToken = $this->tryConsumeToken(TokenType::KEYWORD_MUT);
		$mutable = ($firstToken !== null);
		$type = $this->tryConsumeTypeExpression();
		if (!$mutable) {
			$firstToken = $this->tryConsumeToken(TokenType::KEYWORD_LET);
			if ($type === null && $firstToken === null) {
				return null;
			}
		}
		$tokenIdentifier = $this->consumeTokenOrError(TokenType::IDENTIFIER);
		$identifier = $this->visitNode(
			(object) [
				'node' => NodeType::IDENTIFIER,
				'name' => $tokenIdentifier->value,
				'line' => $tokenIdentifier->line, 'col' => $tokenIdentifier->col,
			]
		);
		if ($identifier === null || $identifier->node !== NodeType::IDENTIFIER) {
			throw new CompilerRuntimeException("Node type should be 'Identifier'");
		}
		return $this->visitNodeOrNoOp(
			(object) [
				'node' => NodeType::DECLARATION_VARIABLE,
				'identifier' => $identifier,
				'mutable' => $mutable,
				'type' => $type, // can be null
				'line' => $firstToken->line ?? $type->line,
				'col' => $firstToken->col ?? $type->col,
			]
		);
	}

	/**
	 * @return Node|null
	 */
	protected function tryConsumeStmtDeclType(): ?object {
		$tokenType = $this->tryConsumeToken(TokenType::KEYWORD_TYPE);
		if ($tokenType === null) {
			return null;
		}
		$tokenIdentifier = $this->consumeTokenOrError(TokenType::TYPE_NAME);
		$identifier = $this->visitNode(
			(object) [
				'node' => NodeType::IDENTIFIER,
				'name' => $tokenIdentifier->value,
				'line' => $tokenIdentifier->line, 'col' => $tokenIdentifier->col,
			]
		);
		if ($identifier === null || $identifier->node !== NodeType::IDENTIFIER) {
			throw new CompilerRuntimeException("Node type should be 'Identifier'");
		}
		$this->consumeTokenValueOrError(TokenType::OPERATOR, '=');
		$type = $this->tryConsumeTypeExpression() ?? $this->error('Expected type');
		return $this->visitNodeOrNoOp(
			(object) [
				'node' => NodeType::DECLARATION_TYPE,
				'identifier' => $identifier,
				'type' => $type,
				'line' => $tokenType->line, 'col' => $tokenType->col,
			]
		);
	}

	/**
	 * @return Node|null
	 */
	protected function tryConsumeStmtDeclVariable(): ?object {
		$declaration = $this->tryConsumeDeclVariable();
		if ($declaration === null) {
			return null;
		}
		if ($declaration->node !== NodeType::DECLARATION_VARIABLE) {
			throw new CompilerRuntimeException("Node type should be 'VariableDeclaration'");
		}
		if ($this->tryConsumeTokenValue(TokenType::OPERATOR, '=')) {
			$value = $this->tryConsumeExpression() ?? $this->error("Expected expression #6");
			$this->consumeTokenOrError(TokenType::SEMICOLON);
			if ($declaration->type === null) {
				$declaration = (object) [
					'node' => NodeType::DECLARATION_VARIABLE,
					'identifier' => $declaration->identifier,
					'mutable' => $declaration->mutable,
					'type' => (object) [
						'node' => NodeType::TYPE_EXPRESSION,
						'name' => Lang::TYPE_INFER,
						'line' => $declaration->line, 'col' => $declaration->col,
					], // $this->inferType($value, $declaration),
					'line' => $declaration->line, 'col' => $declaration->col,
				];
			}
			return $this->visitNodeOrNoOp(
				(object) [
					'node' => NodeType::EXPRESSION_ASSIGNMENT,
					'operator' => '=',
					'left' => $declaration,
					'right' => $value,
					'line' => $declaration->line, 'col' => $declaration->col,
				]
			);
		}
		if ($declaration->type === null) {
			$this->error("Expected variable type '{$declaration->identifier->name}'");
		}
		$this->consumeTokenOrError(TokenType::SEMICOLON);
		return $declaration;
	}

	/* *
	 * @param object|NodeExpressionBinary|NodeExpressionUnary|NodeExpressionAssignment|NodeExpressionUpdate|NodeLiteralInt|NodeLiteralBool $value
	 * @param object|Node $node
	 * @return string
	 * /
	/* protected function inferType(object $value, object $node): string {
		// todo: type sizes
		if ($value->node === NodeType::LITERAL_INT) {
			$type = 'Int32';
		} else if ($value->node === NodeType::LITERAL_BOOL) {
			$type = 'Bool';
		} else if ($value->node === NodeType::EXPRESSION_UNARY) {
			$type = ($value->operator === '!') ? 'Bool' : 'Int32';
		} else if ($value->node === NodeType::EXPRESSION_BINARY) {
			$type = ($value->operator === '&&' || $value->operator === '||') ? 'Bool' : 'Int32';
		} else if ($value->node === NodeType::EXPRESSION_ASSIGNMENT) {
			if ($value->operator === '=') {
				$type = $value->left->type->name;
			} else if ($value->operator === '|=' || $value->operator === '&=') {
				$type = 'Bool';
			} else {
				$type = 'Int32';
			}
		} else if ($value->node === NodeType::EXPRESSION_UPDATE) {
			$type = 'Int32';
		// } else if ($value->node === NodeType::LITERAL_STRING) {
			// $type = 'String';
		} else {
			$nodeType = Lang::getNodeTypeName($value);
			throw new CompilerRuntimeException("Could not infer type for node '{$nodeType}'");
		}
		return (object) [
			'node' => NodeType::TYPE_EXPRESSION,
			'name' => $type,
			'line' => $node->line, 'col' => $node->col,
		];
	} */

	/**
	 * @return Node|null
	 */
	protected function tryConsumeStmtExpression(): ?object {
		$value = $this->tryConsumeExpression();
		if ($value === null) {
			return null;
		}
		$this->consumeTokenOrError(TokenType::SEMICOLON);
		return $this->visitNodeOrNoOp(
			(object) [
				'node' => NodeType::STATEMENT_EXPRESSION,
				'value' => $value,
				'line' => $value->line, 'col' => $value->col,
			]
		);
	}

	/**
	 * @return Node|null
	 */
	protected function tryConsumeAssignmentExpression(): ?object {
		$tokenIdentifier = $this->peekToken(0, TokenType::IDENTIFIER);
		if ($tokenIdentifier === null) {
			return null;
		}
		$tokenOperator = $this->peekToken(1, TokenType::OPERATOR);
		if (
			$tokenOperator === null || (
				$tokenOperator->value !== '=' &&
				$tokenOperator->value !== '+=' &&
				$tokenOperator->value !== '-=' &&
				$tokenOperator->value !== '*=' &&
				$tokenOperator->value !== '|=' &&
				$tokenOperator->value !== '&=' &&
				$tokenOperator->value !== '^='
			)
		) {
			return null;
		}
		$this->consume(2);
		$value = $this->tryConsumeExpression() ?? $this->error("Expected expression #7");
		$identifier = $this->visitNode(
			(object) [
				'node' => NodeType::IDENTIFIER,
				'name' => $tokenIdentifier->value,
				'line' => $tokenIdentifier->line, 'col' => $tokenIdentifier->col,
			]
		);
		if ($identifier === null || $identifier->node !== NodeType::IDENTIFIER) {
			throw new CompilerRuntimeException("Node type should be 'Identifier'");
		}
		return $this->visitNode(
			(object) [
				'node' => NodeType::EXPRESSION_ASSIGNMENT,
				'operator' => $tokenOperator->value,
				'left' => $identifier,
				'right' => $value,
				'line' => $tokenIdentifier->line, 'col' => $tokenIdentifier->col,
			]
		);
	}

	/**
	 * @return Node|null
	 */
	protected function tryConsumeNoOp(): ?object {
		$token = $this->tryConsumeToken(TokenType::SEMICOLON);
		if ($token === null) {
			return null;
		}
		return $this->visitNodeOrNoOp(
			(object) [
				'node' => NodeType::STATEMENT_NOOP,
				'line' => $token->line, 'col' => $token->col,
			]
		);
	}

	/**
	 * @param bool $isElse
	 * @return Node|null
	 */
	protected function tryConsumeStmt(bool $isElse = false): ?object {
		return (
			$this->tryConsumeStmtIOPrint() ?? // todo: temporary io.print

			$this->tryConsumeStmtReturn() ??
			$this->tryConsumeStmtIf($isElse) ??
			$this->tryConsumeStmtWhileLoop() ??
			$this->tryConsumeStmtForLoop() ??
			$this->tryConsumeStmtDeclType() ??
			$this->tryConsumeStmtDeclVariable() ??
			$this->tryConsumeStmtExpression() ??
			$this->tryConsumeNoOp()
		);
	}

	/**
	 * @param Node|object $node
	 * @return Node|null
	 */
	protected function visitNode(object $node): ?object {
		if ($this->hasExtensions) {
			foreach ($this->extensions as $extension) {
				try {
					$node = $extension->transformASTNode($node);
				} catch (CompilerException $exception) {
					$className = get_class($extension);
					throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col} [$className]: {$exception->getMessage()}", E_USER_ERROR, $exception);
				}
				if ($node === null) {
					return null;
				}
			}
		}
		return $node;
	}

	/**
	 * @param Node|object $node
	 * @return Node|null
	 */
	protected function visitNodeRoot(object $node): ?object {
		foreach ($this->extensionsRoot as $extension) {
			try {
				$node = $extension->transformASTRootNode($node);
			} catch (CompilerException $exception) {
				$className = get_class($extension);
				throw new CompilerException("[$className] {$exception->getMessage()}", E_USER_ERROR, $exception);
			}
			if ($node === null) {
				return null;
			}
		}
		return $node;
	}

	/**
	 * @param Node|object $node
	 * @return Node|null
	 */
	protected function visitNodeOrNoOp(object $node): ?object {
		return $this->visitNode($node) ?? (object) [
			'node' => NodeType::STATEMENT_NOOP,
			'line' => $node->line, 'col' => $node->col,
		];
	}

	/**
	 * @param Token[] $tokens
	 * @param string $fileName
	 * @param CompilerExtension[] $extensions
	 * @return NodeProgram
	 */
	public function parse(array $tokens, string $fileName, $extensions): object {
		$this->extensions = array_filter($extensions, function(CompilerExtension $extension) {
			return $extension->transformMode & TransformMode::AST;
		});
		$this->hasExtensions = (count($this->extensions) > 0);

		$this->extensionsRoot = array_filter($extensions, function(CompilerExtension $extension) {
			return $extension->transformMode & TransformMode::AST_ROOT;
		});

		$this->fileName = $fileName;
		$this->tokens = $tokens;
		$this->tokensCount = count($tokens);
		$this->current = 0;

		$statements = [];
		while ($this->current < $this->tokensCount) {
			$node = $this->tryConsumeStmt();
			if ($node === null) {
				$token = $this->peekUnchecked(0);
				throw new CompilerException("{$this->fileName}:{$token->line}:{$token->col}: Unexpected token {$this->displayToken($token)}");
			}
			$statements[] = $node;
		}

		/** @var NodeProgram|null $nodeProgram */
		$nodeProgram = $this->visitNodeRoot(
			(object) [
				'node' => NodeType::PROGRAM,
				'statements' => $statements,
				'line' => 1, 'col' => 1,
			]
		);
		if ($nodeProgram === null || $nodeProgram->node !== NodeType::PROGRAM) {
			throw new CompilerRuntimeException("Node type should be 'Program'");
		}
		return $nodeProgram;
	}

	protected function displayToken(Token $token): string {
		if ($token->isKeyword()) {
			$display = $this->keywordNames[$token->value] ?? 'UNKNOWN_KEYWORD';
		} else {
			$display = Lang::$tokens[$token->token] ?? 'UNKNOWN_TOKEN';
		}
		if ($token->value !== null) {
			$display .= ":`{$token->value}`";
		} else if (isset(Lang::$controlTokens[$token->token])) {
			return "`{$display}`";
		}
		return $display;
	}

}
