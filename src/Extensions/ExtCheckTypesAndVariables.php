<?php

/**
 * check types, infer types, check variables usage, check variables declarations
 *
 * Class ExtCheckTypesAndVariables
 */
class ExtCheckTypesAndVariables extends CompilerExtension {

	public $transformMode = TransformMode::AST_ROOT;

	/** @var int */
	protected $stackPos;

	/** @var int */
	protected $scopeNesting;

	/** @var array */
	protected $identifiers;

	/** @var array */
	protected $identifiersStack;

	/** @var array */
	protected $declaredTypes;

	/** @var array */
	protected $declaredTypesStack;

	/** @var int */
	protected $scopeBranch;

	/** @var int */
	protected $branchLevel;

	/** @var string */
	protected $fileName;

	protected const ROOT_SCOPE_NESTING = 1;

	protected const BRANCH_MAIN = 0;
	protected const BRANCH_THEN = 1;
	protected const BRANCH_ELSE = 2;

	protected const MAX_BRANCH_LEVEL = 0x8000000000000000; // 9223372036854775808

	public function __construct(string $fileName) {
		$this->fileName = $fileName;
		$this->stackPos = 0;
		$this->scopeNesting = self::ROOT_SCOPE_NESTING;
		$this->identifiers = [];
		$this->identifiersStack = [];
		$this->declaredTypes = [];
		$this->declaredTypesStack = [];
		$this->scopeBranch = self::BRANCH_MAIN;
		$this->branchLevel = 0;
	}

	/**
	 * @param NodeProgram|object $node
	 * @return NodeProgram
	 */
	public function transformASTRootNode(object $node): object {
		foreach ($node->statements as $statement) {
			$this->traverseStmt($statement);
		}
		return $node;
	}

	/**
	 * @param Node|object $node
	 */
	public function traverseStmt(object $node): void {
		if (
			$node->node === NodeType::STATEMENT_EXPRESSION ||
			$node->node === NodeType::STATEMENT_RETURN ||
			$node->node === NodeType::STATEMENT_IO_PRINT // todo: temporary io.print
		) {
			$this->traverseExpression($node->value);
		} else if ($node->node === NodeType::EXPRESSION_ASSIGNMENT) {
			$this->traverseExpressionAssignment($node);
		} else if ($node->node === NodeType::EXPRESSION_UPDATE) {
			$this->traverseIncrement($node->value);
		} else if (
			$node->node === NodeType::STATEMENT_IF ||
			$node->node === NodeType::STATEMENT_ELSE_IF
		) {
			$this->traverseStmtIf($node);
		} else if ($node->node === NodeType::STATEMENT_WHILE_LOOP) {
			$this->traverseStmtWhileLoop($node);
		} else if ($node->node === NodeType::STATEMENT_FOR_LOOP) {
			$this->traverseStmtForLoop($node);
		} else if ($node->node === NodeType::DECLARATION_VARIABLE) {
			$this->traverseDeclVariable($node);
		} else if ($node->node === NodeType::DECLARATION_TYPE) {
			$this->traverseDeclType($node);
		}
	}

	/**
	 * @param NodeExpressionUnary|NodeExpressionBinary|NodeExpressionUpdate|Node|object $node
	 * @return NodeTypeExpression
	 */
	protected function traverseExpression(object $node): object {
		if ($node->node === NodeType::EXPRESSION_ASSIGNMENT) {
			return $this->traverseExpressionAssignment($node);
		} else if ($node->node === NodeType::EXPRESSION_BINARY) {
			$typeLeft = $this->traverseExpression($node->left);
			$typeRight = $this->traverseExpression($node->right);
			if ($node->operator === '||' || $node->operator === '&&') {
				if ($typeLeft->name !== 'Bool' || $typeRight->name !== 'Bool') {
					throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Binary operator '{$node->operator}' is not defined for types '{$typeLeft->name}', {$typeRight->name}");
				}
				return $typeLeft;
			} else if (
				$node->operator === '==' || $node->operator === '!=' ||
				$node->operator === '>=' || $node->operator === '<=' ||
				$node->operator === '<' || $node->operator === '>'
			) {
				if ($typeLeft->name !== 'Int32' || $typeRight->name !== 'Int32') {
					throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Binary operator '{$node->operator}' is not defined for types '{$typeLeft->name}', {$typeRight->name}");
				}
				return (object) [
					'node' => NodeType::TYPE_EXPRESSION,
					'name' => 'Bool', // todo: constant
					'line' => $node->line, 'col' => $node->col,
				];
			} else if (
				$node->operator === '+' || $node->operator === '-' ||
				$node->operator === '*' || $node->operator === '/' || $node->operator === '%' ||
				$node->operator === '&' || $node->operator === '|' || $node->operator === '^' ||
				$node->operator === '>>' || $node->operator === '<<'
			) {
				if ($typeLeft->name !== 'Int32' || $typeRight->name !== 'Int32') {
					throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Binary operator '{$node->operator}' is not defined for types '{$typeLeft->name}', {$typeRight->name}");
				}
				return $typeLeft;
			} else {
				throw new CompilerRuntimeException("Unsupported binary operator '{$node->operator}'");
			}
		} else if ($node->node === NodeType::EXPRESSION_UNARY) {
			$type = $this->traverseExpression($node->value);
			if ($node->operator === '!') {
				if ($type->name !== 'Bool') {
					throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Unary operator '{$node->operator}' is not defined for expression of type '{$type->name}'");
				}
			} else if ($node->operator === '+' || $node->operator === '-' || $node->operator === '~') {
				if ($type->name !== 'Int32') {
					throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Unary operator '{$node->operator}' is not defined for expression of type '{$type->name}'");
				}
			} else {
				throw new CompilerRuntimeException("Unsupported unary operator '{$node->operator}'");
			}
			return $type;
		} else if ($node->node === NodeType::EXPRESSION_UPDATE) {
			$type = $this->traverseIncrement($node->value);
			if ($type->name !== 'Int32') {
				throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Update operator '{$node->operator}' is not defined for variable of type '{$type->name}'");
			}
			return $type;
		} else if ($node->node === NodeType::LITERAL_INT) {
			return (object) [
				'node' => NodeType::TYPE_EXPRESSION,
				'name' => 'Int32', // todo: constant
				'line' => $node->line, 'col' => $node->col,
			];
		} else if ($node->node === NodeType::LITERAL_BOOL) {
			return (object) [
				'node' => NodeType::TYPE_EXPRESSION,
				'name' => 'Bool', // todo: constant
				'line' => $node->line, 'col' => $node->col,
			];
		} else if ($node->node === NodeType::IDENTIFIER) {
			return $this->traverseExpressionIdentifier($node);
		}
		throw new CompilerRuntimeException("Unexpected node type '{$node->node}'");
	}

	/**
	 * @param NodeTypeExpression|object $type
	 * @return NodeTypeExpression|object
	 */
	protected function resolveType(object $type): object {
		// todo: generics support
		$declaredType = $this->declaredTypes[$type->name] ?? null;
		if ($declaredType !== null) {
			return (object) [
				'node' => NodeType::TYPE_EXPRESSION,
				'name' => $declaredType->name,
				'line' => $type->line, 'col' => $type->col,
			];
		}
		return $type;
	}

	/**
	 * @param NodeDeclVariable|object $node
	 * @param NodeTypeExpression|object|null $assignExprType
	 */
	protected function traverseDeclVariable(object $node, ?object $assignExprType = null): void {
		$identifier = $node->identifier->name;
		if (isset($this->identifiers[$identifier])) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' is already declared");
		}
		if ($node->type->name === Lang::TYPE_INFER) {
			if ($assignExprType === null) {
				throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' declared to infer type but not initialized. Provide it's initial value or explicitly specify the type");
			}
			$type = $assignExprType;
		} else {
			$type = $this->resolveType($node->type);
		}
		$this->identifiers[$identifier] = (object) [
			'initScope' => 0,
			'mutable' => $node->mutable,
			'possibleInitThen' => 0,
			'possibleInitElse' => 0,
			'possibleInit' => false,
			'then' => 0,
			'else' => 0,
			'type' => $type,
		];
		$this->identifiersStack[] = $identifier;
	}

	/**
	 * @param NodeDeclType|object $node
	 */
	protected function traverseDeclType(object $node): void {
		$typeName = $node->identifier->name;
		// if (isset($this->declaredTypes[$typeName])) { throw new CompilerException("Type '{$typeName}' is already declared") }
		$this->declaredTypes[$typeName] = $this->resolveType($node->type);
		$this->declaredTypesStack[] = $typeName;
	}

	/**
	 * @param NodeStmtWhileLoop|object $node
	 */
	protected function traverseStmtWhileLoop(object $node): void {
		/** @var Node|NodeExpressionBinary $condition */
		$condition = $node->condition;

		[$prevStackPos, $prevTypesStackPos, $prevBranch] = $this->beginConditionalBlock($node);
			$this->traverseExpression($condition);

			foreach ($node->statements as $statement) {
				$this->traverseStmt($statement);
			}
		$this->endConditionalBlock($prevStackPos, $prevTypesStackPos, $prevBranch);
	}

	/**
	 * @param NodeStmtForLoop|object $node
	 */
	protected function traverseStmtForLoop(object $node): void {
		[$prevStackPos, $prevTypesStackPos, $prevBranch] = $this->beginConditionalBlock($node);

			$identifier = $node->identifier;
			$this->traverseExpressionAssignment((object) [
				'node' => NodeType::EXPRESSION_ASSIGNMENT,
				'operator' => '=',
				'left' => $identifier,
				'right' => $node->from,
				'line' => $node->from->line, 'col' => $node->from->col,
			]);
			if ($identifier->node === NodeType::DECLARATION_VARIABLE) {
				$identifier = $identifier->identifier;
			}

			$type = $this->traverseIncrement($identifier);
			if ($type->name !== 'Int32') {
				throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Counter variable '{$identifier->name}' of type '{$type->name}' should have type 'Int32'");
			}

			$this->traverseExpression($node->to); // todo: ? ensure no modification of identifier inside this
			// todo: check comparison operator supports node->to & identifier types

			foreach ($node->statements as $statement) {
				$this->traverseStmt($statement);
			}
		$this->endConditionalBlock($prevStackPos, $prevTypesStackPos, $prevBranch);
	}

	/**
	 * @param NodeStmtIf|NodeStmtElseIf|object $node
	 */
	protected function traverseStmtIf(object $node): void {
		if ($node->else === null) {
			$this->traverseThenBlock(false, false, $node->then);
		} else {
			$this->traverseThenBlock(false, true, $node->then);
			$this->traverseThenBlock(true, true, $node->else);
		}
	}


	/**
	 * @param NodeIdentifier|object $node
	 * @return NodeTypeExpression
	 */
	protected function traverseExpressionIdentifier(object $node): object {
		$identifier = $node->name;
		if (!isset($this->identifiers[$identifier])) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' is not declared");
		}
		$data = $this->identifiers[$identifier];
		if ($data->initScope === 0) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Usage of uninitialized variable '{$identifier}'");
		}
		return $data->type;
	}

	/**
	 * @param NodeIdentifier|object $node
	 * @return NodeTypeExpression
	 */
	protected function traverseIncrement(object $node): object {
		$identifier = $node->name;
		if (!isset($this->identifiers[$identifier])) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' is not declared");
		}
		$data = $this->identifiers[$identifier];
		if (!$data->mutable) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Can't modify an immutable variable '{$identifier}'");
		}
		if ($data->initScope === 0) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Usage of uninitialized variable '{$identifier}'");
		}
		return $data->type;
	}


	/**
	 * @param NodeExpressionAssignment|object $node
	 * @return NodeTypeExpression
	 */
	protected function traverseExpressionAssignment(object $node): object {
		// todo: for `x = () => { ...x() }` move traverseExpression after declaration (only get type from a function), otherwise will fail because variable is not declared
		$type = $this->traverseExpression($node->right);


		if ($node->left->node === NodeType::DECLARATION_VARIABLE) {
			$this->traverseDeclVariable($node->left, $type);
			$identifier = $node->left->identifier->name;
		} else if ($node->left->node === NodeType::IDENTIFIER) {
			$identifier = $node->left->name;
		// } else if (...) {
			// other assignable node types
		} else {
			$nodeType = Lang::getNodeTypeName($node->left);
			throw new CompilerRuntimeException("{$this->fileName}:{$node->line}:{$node->col}: Wrong node type: '{$nodeType}', need to be assignable VariableDeclaration or Identifier");
		}


		if (!isset($this->identifiers[$identifier])) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' is not declared");
		}
		$data = $this->identifiers[$identifier];


		if ($node->operator === '=') {
			// write
			if (!$data->mutable) {
				if ($data->initScope > 0 || $data->possibleInit) {
					throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Can't modify an immutable variable '{$identifier}'");
				}
			}
			if ($data->initScope === 0) {
				$data->initScope = $this->scopeNesting;
				$data->possibleInit = true;
				if ($this->scopeBranch === self::BRANCH_THEN) {
					$data->then |= $this->branchLevel;
					$data->possibleInitThen |= $this->branchLevel;
				} else if ($this->scopeBranch === self::BRANCH_ELSE) {
					$data->else = 1;
					$data->possibleInitElse = 1;
				}
			}

			// todo: implement more complex assignment type check
			if ($data->type->name !== $type->name) {
				throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Can't assign variable '{$identifier}' of type '{$data->type->name}' with value of type '{$type->name}'");
			}
			return $data->type;
		}

		if (
			$node->operator === '+=' || $node->operator === '-=' || $node->operator === '*='
			// $node->operator === '/=' || $node->operator === '%='
		) {
			if ($data->type->name !== 'Int32' || $type->name !== 'Int32') {
				throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Assignment operator '{$node->operator}' is not defined for types '{$data->type->name}', '{$type->name}'");
			}
		} else if ($node->operator === '|=' || $node->operator === '&=' || $node->operator === '^=') {
			if ($data->type->name !== 'Bool' || $type->name !== 'Bool') {
				throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Assignment operator '{$node->operator}' is not defined for types '{$data->type->name}', '{$type->name}'");
			}
		} else {
			throw new CompilerRuntimeException("Unsupported assignment operator '{$node->operator}'");
		}

		// read + write
		if (!$data->mutable) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Can't modify an immutable variable '{$identifier}'");
		}
		if ($data->initScope === 0) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Usage of uninitialized variable '{$identifier}'");
		}

		return $data->type;
	}


	/**
	 * @param bool $isElse
	 * @param bool $hasElse
	 * @param NodeScope|Node|object $node
	 */
	protected function traverseThenBlock(bool $isElse, bool $hasElse, object $node): void {
		if ($this->branchLevel >= self::MAX_BRANCH_LEVEL) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Maximum 64 levels of conditional nesting exceeded");
		}
		$prevBranch = $this->scopeBranch;
		$this->scopeBranch = $isElse ? self::BRANCH_ELSE : self::BRANCH_THEN;
		$this->branchLevel = ($this->branchLevel === 0)
			? self::ROOT_SCOPE_NESTING
			: ($this->branchLevel << 1);

		if (
			$node->node === NodeType::STATEMENT_ELSE_IF ||
			$node->node === NodeType::STATEMENT_IF
		) {
			$this->scopeNesting++;
				$this->traverseStmtIf($node);
			$this->scopeBranch = $prevBranch;
			$this->scopeNesting--;
			$this->branchLevel = ($this->branchLevel >> 1);
			return;
		}

		[$prevStackPos, $prevTypesStackPos] = [$this->beginBlock(), count($this->declaredTypesStack)];
			if ($node->node === NodeType::STATEMENT_SCOPE) {
				foreach ($node->statements as $statement) {
					$this->traverseStmt($statement);
				}
			} else {
				$this->traverseStmt($node);
			}
		$this->scopeBranch = $prevBranch;
		$this->endBlock($prevStackPos, $prevTypesStackPos, $isElse, $hasElse);
		$this->branchLevel = ($this->branchLevel >> 1);
	}



	/**
	 * @param Node|object $node
	 * @return array
	 */
	protected function beginConditionalBlock(object $node): array {
		if ($this->branchLevel >= self::MAX_BRANCH_LEVEL) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Maximum 64 levels of conditional nesting exceeded");
		}
		$prevBranch = $this->scopeBranch;
		$this->scopeBranch = self::BRANCH_THEN;
		$this->branchLevel = ($this->branchLevel === 0)
			? self::ROOT_SCOPE_NESTING
			: ($this->branchLevel << 1);

		return [$this->beginBlock(), count($this->declaredTypesStack), $prevBranch];
	}

	protected function endConditionalBlock(int $prevStackPos, int $prevTypesStackPos, int $prevBranch): void {
		$this->scopeBranch = $prevBranch;
		$this->endBlock($prevStackPos, $prevTypesStackPos, false, false);
		$this->branchLevel = ($this->branchLevel >> 1);
	}

	protected function beginBlock(): int {
		$this->scopeNesting++;
		return $this->stackPos;
	}

	/**
	 * remove variables declared during block body, restore stack pointer
	 * @param int $prevStackPos
	 * @param int $prevTypesStackPos
	 * @param bool $isElse
	 * @param bool $hasElse
	 */
	protected function endBlock(int $prevStackPos, int $prevTypesStackPos, bool $isElse = false, bool $hasElse = false): void {
		$this->scopeNesting--;
		foreach ($this->identifiers as $identifier => $data) {
			if ($data === null) {
				continue;
			}

			if (
				$data->initScope > $this->scopeNesting ||
				$data->initScope === 0
			) {
				$data->initScope = 0;
				$data->possibleInit = false;

				if ($isElse || !$hasElse) { // isElse || (isThen && !hasElse)
					if ($isElse) { // merge branches then & else
						$isInitThen = ($data->then & $this->branchLevel);
						if ($isInitThen && $data->else) {
							$data->initScope = $this->scopeNesting;

							if ($this->scopeBranch === self::BRANCH_ELSE) {
								$data->then &= (~$this->branchLevel);
								$data->then |= ($this->branchLevel >> 1);
								$data->else = 0;
							} else if ($this->scopeBranch === self::BRANCH_THEN) {
								$data->then &= (~$this->branchLevel);
								$data->else = 1;
							} else { // self::BRANCH_MAIN
								$data->then = 0;
								$data->else = 0;
							}

						} else {
							$data->else = 0;
							$data->then &= (~$this->branchLevel);
						}
					} else {
						$data->else = 0;
					}

					$isPossibleInitThen = ($data->possibleInitThen & $this->branchLevel);
					if ($isPossibleInitThen || $data->possibleInitElse) {
						$data->possibleInit = true;

						if ($this->scopeBranch === self::BRANCH_THEN) {
							$data->possibleInitThen &= (~$this->branchLevel);
							$data->possibleInitThen |= ($this->branchLevel >> 1);
							$data->possibleInitElse = 0;
						} else if ($this->scopeBranch === self::BRANCH_ELSE) {
							$data->possibleInitThen &= (~$this->branchLevel);
							$data->possibleInitElse = 1;
						} else { // self::BRANCH_MAIN
							$data->possibleInitThen = 0;
							$data->possibleInitElse = 0;
						}

					}
				}
			}

		}

		$removeCount = $this->stackPos - $prevStackPos;
		if ($removeCount > 0) {
			$removed = array_splice($this->identifiersStack, $prevStackPos, $removeCount);
			foreach ($removed as $identifier) {
				$this->identifiers[$identifier] = null; // or unset($this->identifiers[$identifier])
			}
			$this->stackPos = $prevStackPos;
		}

		$removeTypesCount = count($this->declaredTypesStack) - $prevTypesStackPos;
		if ($removeTypesCount > 0) {
			$removed = array_splice($this->declaredTypesStack, $prevTypesStackPos, $removeTypesCount);
			foreach ($removed as $typeName) {
				$this->declaredTypes[$typeName] = null; // or unset($this->identifiers[$identifier])
			}
		}
	}

}