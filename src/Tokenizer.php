<?php

class Tokenizer {

	/** @var string */
	protected $code;

	/** @var int */
	protected $codeLength;

	/** @var int */
	protected $current;

	/** @var int */
	protected $line;

	/** @var int */
	protected $colStart;

	/** @var array */
	protected $controlTokens;

	/** @var int */
	protected $curlyNesting;

	/** @var int */
	protected $typeParamNesting;

	/** @var array */
	protected $operators;

	/* * @var array */
	// protected $keywords;

	/** @var array */
	protected $typeNames;

	/** @var int */
	protected $typeDeclarationMode;

	/** @var bool */
	protected $functionReturnTypeDefinition;

	/** @var Token[] */
	protected $tokens;

	protected const NEW_LINE = "\n";

	public function __construct() {
		$this->controlTokens = [];
		foreach (Lang::$controlTokens as $tokenType => $_) {
			$tokenValue = Lang::$tokens[$tokenType];
			$this->controlTokens[$tokenValue] = $tokenType;
		}
		/* $this->keywords = [];
		foreach (Lang::$keywords as $keyword => $keywordId) {
			$this->keywords[$keyword] = true;
		} */
	}

	protected function init(): void {
		$this->current = 0;
		$this->line = 1;
		$this->colStart = 0;
		$this->curlyNesting = 0;
		$this->typeParamNesting = 0;
		$this->functionReturnTypeDefinition = false;
		$this->tokens = [];

		$this->operators = Lang::$operators;

		$this->typeNames = [];
		foreach (Lang::$types as $typeName => $namespace) {
			$this->typeNames[$typeName] = 0;
		}
		$this->typeDeclarationMode = self::TYPE_DECLARATION_NONE;
	}


	/* protected function isKeyword(string $value): bool {
		return isset(Lang::$keywords[$value]);
	} */

	protected function getKeywordId(string $value): int {
		return Lang::$keywords[$value] ?? 0;
	}

	/* protected function isTypeDeclarationKeyword(string $value): bool {
		return in_array($value, Lang::$typeDeclarationKeywords);
	} */

	protected function isTypeDeclarationKeyword(int $tokenType): bool {
		return isset(Lang::$typeDeclarationKeywords[$tokenType]);
		// return in_array($value, Lang::$typeDeclarationKeywords);
	}

	protected function isAlpha(string $c): bool {
		return $c === '_' || ctype_alpha($c);
	}

	protected function isAlphaDigit(string $c): bool {
		return $c === '_' || ctype_alnum($c);
	}

	protected function isDigit(string $c): bool {
		return ctype_digit($c);
	}

	protected function isWhitespace(string $c): bool {
		return ctype_space($c);
	}

	/* protected function isTokenTypeAt(int $index, $tokenType): bool {
		return $this->tokens[$index]->token === $tokenType;
	} */

	/**
	 * check token type at index [length - offset]
	 * last item is 1, before last is 2, etc
	 *
	 * @param int $offset
	 * @param int $tokenType
	 * @return bool
	 */
	protected function isTokenTypeFromEnd(int $offset, int $tokenType): bool {
		$index = count($this->tokens) - $offset;
		return (
			$index >= 0 &&
			$this->tokens[$index]->token === $tokenType
		);
	}


	protected function getLineAndCol(): array {
		$line = $this->line;
		$col = $this->current - $this->colStart + 1;
		return [$line, $col];
	}

	protected function newLine(): void {
		$this->line++;
		$this->colStart = $this->current;
	}

	protected function consume($length): void {
		$this->current += $length;
	}

	protected function peekUnchecked(int $length, int $offset = 0): string {
		return substr($this->code, $this->current + $offset, $length);
	}

	protected function peek(int $length, int $offset = 0): ?string {
		if ($this->current + $offset + $length >= $this->codeLength) {
			return null;
		}
		return substr($this->code, $this->current + $offset, $length);
	}

	protected function insertTokenAfter(int $index, Token $token): void {
		array_splice($this->tokens, $index, 0, [$token]);
	}


	protected function tryConsumeValue(string $value): bool {
		$length = strlen($value);
		if ($this->peek($length) !== $value) {
			return false;
		}
		$this->consume($length);
		return true;
	}

	protected function tryConsumeToken(int $tokenType, string $value): ?Token {
		$length = strlen($value);
		if ($this->peek($length) !== $value) {
			return null;
		}
		[$line, $col] = $this->getLineAndCol();
		$this->consume($length);
		return new Token($tokenType, $line, $col, $value);
	}

	protected function tryConsumeControl(): ?Token {
		$char = $this->peekUnchecked(1);
		$tokenType = $this->controlTokens[$char] ?? null;
		if ($tokenType === null) {
			return null;
		}

		[$line, $col] = $this->getLineAndCol();
		if ($tokenType === TokenType::CURLY_OPEN) {
			if ($this->functionReturnTypeDefinition) {
				// curly nesting was already incremented. clear functionReturnTypeDefinition flag
				$this->functionReturnTypeDefinition = false;
			} else {
				$this->curlyNesting++;
			}
		} else if ($tokenType === TokenType::CURLY_CLOSE) {
			$this->curlyNesting--;
			if ($this->curlyNesting < 0) {
				throw new CompilerException("{$this->fileName}:{$line}:{$col}: Unexpected token '}'");
			}
			$this->popRegisteredTypes();
		}

		$this->consume(1);
		return new Token($tokenType, $line, $col);
	}

	protected function tryConsumeOperator(): ?Token {
		foreach ($this->operators as $operator => $priority) {
			$token = $this->tryConsumeToken(TokenType::OPERATOR, $operator);
			if ($token !== null) {
				return $token;
			}
		}
		return null;
	}

	protected function tryConsumeIntLiteral(): ?Token {
		$c = $this->peekUnchecked(1);
		$length = 1;
		if ($c === '-') {
			if ($this->current + 2 >= $this->codeLength) {
				return null;
			}
			$c = $this->peekUnchecked(1, 1);
			$length++;
		}
		if (!$this->isDigit($c)) {
			return null;
		}
		while ($this->current + $length < $this->codeLength) {
			$c = $this->peekUnchecked(1, $length);
			if (!$this->isDigit($c)) {
				break;
			}
			$length++;
		}

		[$line, $col] = $this->getLineAndCol();
		$value = $this->peekUnchecked($length);
		$this->consume($length);
		return new Token(TokenType::INT, $line, $col, $value);
	}

	protected function tryConsumeStringLiteral(): ?Token {
		$c = $this->peekUnchecked(1);
		if ($c !== '"' && $c !== '`') {
			return null;
		}
		$quoteType = $c;
		$length = 1;
		$closedQuote = false;
		while ($this->current + $length < $this->codeLength) {
			$c = $this->peekUnchecked(1, $length);
			// todo: handle \\ \n \r \t \"
			if ($c === self::NEW_LINE) {
				break;
			}
			if ($c === $quoteType) {
				$length++;
				$closedQuote = true;
				break;
			}
			$length++;
		}

		if (!$closedQuote) {
			$line = $this->line;
			$col = $this->current + $length - $this->colStart + 1;
			throw new CompilerException("{$this->fileName}:{$line}:{$col}: Expected '{$quoteType}'");
		}

		[$line, $col] = $this->getLineAndCol();
		$value = $this->peekUnchecked($length - 2, 1); // "[value]"
		$this->consume($length);

		return new Token(TokenType::STRING, $line, $col, $value);
	}

	protected function tryConsumeComment(): bool {
		if ($this->tryConsumeValue('//')) {
			$length = 0;
			while ($this->current + $length < $this->codeLength) {
				if ($this->peekUnchecked(1, $length) === self::NEW_LINE) {
					break;
				}
				$length++;
			}
			$this->consume($length);
			return true;
		}

		if ($this->tryConsumeValue('/*')) { // todo: nested comments
			$length = 0;
			while ($this->current + $length < $this->codeLength) {
				$c = $this->peekUnchecked(1, $length);
				if ($c === self::NEW_LINE) {
					$this->newLine();
				} else if (
					$c === '*' &&
					$this->peek(1, $length + 1) === '/'
				) {
					$length += 2;
					break;
				}
				$length++;
			}
			$this->consume($length);
			return true;
		}

		return false;
	}

	protected function tryConsumeWhitespace(): bool {
		$c = $this->peekUnchecked(1);
		if (!$this->isWhitespace($c)) {
			return false;
		}
		if ($c === self::NEW_LINE) {
			$this->newLine();
		}
		$this->consume(1);
		return true;
	}

	protected function tryConsumeWord(bool $isTypeDeclaration = false): ?Token {
		$c = $this->peekUnchecked(1);
		if (!$this->isAlpha($c)) {
			return null;
		}
		$length = 1;
		while ($this->current + $length < $this->codeLength) {
			$c = $this->peekUnchecked(1, $length);
			if (!$this->isAlphaDigit($c)) {
				break;
			}
			$length++;
		}

		[$line, $col] = $this->getLineAndCol();
		$value = $this->peekUnchecked($length);
		$this->consume($length);

		$keywordId = $this->getKeywordId($value);
		if ($keywordId) {
			if ($this->isTypeDeclarationKeyword($keywordId)) {
				if ($this->typeDeclarationMode !== self::TYPE_DECLARATION_NONE) {
					throw new CompilerException("{$this->fileName}:{$line}:{$col}: Unexpected KEYWORD:`{$value}`");
				}
				$this->typeDeclarationMode = ($keywordId === TokenType::KEYWORD_TYPE)
					? self::TYPE_DECLARATION_TYPE_REGISTER
					: self::TYPE_DECLARATION_OTHER_REGISTER;
				$this->typeParamNesting = 0;
			}
			return new Token($keywordId, $line, $col);
		}
		if ($isTypeDeclaration || $this->isTypeRegisteredAtNesting($value, $this->curlyNesting)) {
			$tokenType = TokenType::TYPE_NAME;
		} else {
			$tokenType = TokenType::IDENTIFIER;
		}
		return new Token($tokenType, $line, $col, $value);
	}

	protected function isTypeRegisteredAtNesting(string $value, int $nesting): bool {
		return (
			isset($this->typeNames[$value]) &&
			$this->typeNames[$value] <= $nesting
		);
	}

	protected function registerTypeAtNesting(string $value, int $nesting): void {
		$this->typeNames[$value] = $nesting;
	}

	protected function popRegisteredTypes(): void {
		foreach ($this->typeNames as $typeName => $nesting) {
			if ($nesting === null) {
				continue;
			}
			if ($nesting > $this->curlyNesting) {
				$this->typeNames[$typeName] = null;
			}
		}
	}

	protected const TYPE_DECLARATION_NONE = 0;
	protected const TYPE_DECLARATION_OTHER = 1;
	protected const TYPE_DECLARATION_TYPE = 2;
	protected const TYPE_DECLARATION_OTHER_REGISTER = 3; // -2 = TYPE_DECLARATION_OTHER
	protected const TYPE_DECLARATION_TYPE_REGISTER = 4; // -2 = TYPE_DECLARATION_TYPE

	protected function tryConsumeTypeDeclaration(): ?Token {
		if ($this->typeDeclarationMode === self::TYPE_DECLARATION_NONE) {
			return null;
		}

		$char = $this->peekUnchecked(1);
		$tokenType = null;
		if ($char === '<') {
			$this->typeParamNesting++;
			$tokenType = TokenType::TYPE_PARAM_OPEN;
		} else if ($char === '>') {
			$this->typeParamNesting--;
			if ($this->typeParamNesting < 0) {
				[$line, $col] = $this->getLineAndCol();
				throw new CompilerException("{$this->fileName}:{$line}:{$col}: Unexpected '>'");
			}
			if (
				$this->typeParamNesting === 0 &&
				$this->typeDeclarationMode === self::TYPE_DECLARATION_OTHER_REGISTER
			) {
				$this->typeDeclarationMode -= 2;
			}
			$tokenType = TokenType::TYPE_PARAM_CLOSE;
		} else if ($char === ',') {
			$tokenType = TokenType::COMMA;
		} else if ($char === '=' && $this->typeDeclarationMode === self::TYPE_DECLARATION_TYPE_REGISTER) {
			if ($this->typeParamNesting !== 0) {
				[$line, $col] = $this->getLineAndCol();
				throw new CompilerException("{$this->fileName}:{$line}:{$col}: Expected '>'");
			}
			$tokenType = TokenType::OPERATOR;
			// allow only one '=' during type declaration
			$this->typeDeclarationMode -= 2;
		}

		if ($tokenType !== null) {
			[$line, $col] = $this->getLineAndCol();
			$this->consume(1);
			$value = ($tokenType === TokenType::OPERATOR) ? $char : null;
			$token = new Token($tokenType, $line, $col, $value);
		} else {
			$token = $this->tryConsumeWord(true);
		}

		if ($token && $token->token === TokenType::TYPE_NAME) {
			if ($this->typeDeclarationMode >= self::TYPE_DECLARATION_OTHER_REGISTER) { /* 3 or 4 */
				if ($this->typeParamNesting > 0) {
					// register type if new
					if ($this->isTypeRegisteredAtNesting($token->value, $this->curlyNesting + 1)) {
						throw new CompilerException("{$this->fileName}:{$token->line}:{$token->col}: Duplicate type declaration '{$token->value}'");
					}
					$this->registerTypeAtNesting($token->value, $this->curlyNesting + 1);
				} else {
					// register type
					if ($this->isTypeRegisteredAtNesting($token->value, $this->curlyNesting)) {
						throw new CompilerException("{$this->fileName}:{$token->line}:{$token->col}: Duplicate type declaration '{$token->value}'");
					}
					if ($this->curlyNesting > 0) {
						throw new CompilerException("{$this->fileName}:{$token->line}:{$token->col}: Type declarations only allowed at top level '{$token->value}'");
					}
					$this->registerTypeAtNesting($token->value, 0); // register type at root level
				}
			} else {
				// existing type
				$nesting = ($this->typeParamNesting > 0)
					? $this->curlyNesting + 1
					: $this->curlyNesting;
				if (!$this->isTypeRegisteredAtNesting($token->value, $nesting)) {
					throw new CompilerException("{$this->fileName}:{$token->line}:{$token->col}: Type '{$token->value}' is not declared");
				}
			}
		}

		if (
			$token === null || (
				$token->isKeyword() &&
				$token->token !== TokenType::KEYWORD_EXTENDS &&
				$token->value !== TokenType::KEYWORD_IMPLEMENTS
			)
		) {
			if ($this->typeParamNesting !== 0) {
				[$line, $col] = $this->getLineAndCol();
				throw new CompilerException("{$this->fileName}:{$line}:{$col}: Expected '>'");
			}
			if ($this->typeDeclarationMode === self::TYPE_DECLARATION_TYPE) {
				$this->popRegisteredTypes(); // delete types declared for [curlyNesting + 1] level
			}
			$this->typeDeclarationMode = self::TYPE_DECLARATION_NONE;
		}
		return $token;
	}

	protected function checkFunctionTypeParamsDefinition(): void {
		// $this->tokens = ... `typeOrFnName` `<` `T` `>` `(` `T` `value` `,` `Vec` `<` `T2` `>` `value2` `)` `:`

		$index = count($this->tokens) - 3;
		$indexParenOpen = $index;
		$skipToParenOpen = true;

		while ($index > 1) {
			$token = $this->tokens[$index];
			if ($skipToParenOpen) {
				if ($token->token === TokenType::PAREN_OPEN) {
					if ($this->tokens[$index - 1]->isType(TokenType::OPERATOR, '>')) {
						$indexParenOpen = $index;
						$skipToParenOpen = false;
						$index--;
					} else {
						return;
					}
				}
			} else {
				if ($token->isType(TokenType::OPERATOR, '<')) {
					if ($this->tokens[$index - 1]->token === TokenType::IDENTIFIER) {
						$this->convertTokensToFunctionTypeParams($index, $indexParenOpen);
						$this->convertFunctionArgumentsTypeDefinitions($indexParenOpen + 1, count($this->tokens) - 2);
						// artificially increase curly nesting until next `{` token. allow identifiers captured as types, if they were used in function type args
						// test<T>( ... ): T {
						$this->curlyNesting++;
						$this->functionReturnTypeDefinition = true;
					}
					return;
				} else if (
					$token->token !== TokenType::COMMA &&
					$token->token !== TokenType::IDENTIFIER &&
					$token->token !== TokenType::TYPE_NAME
				) {
					return;
				}
			}
			$index--;
		}
	}

	/**
	 * @param int $indexFrom
	 * @param int $indexTo non-inclusive
	 */
	protected function convertTokensToFunctionTypeParams(int $indexFrom, int $indexTo): void {
		for ($index = $indexFrom; $index < $indexTo; $index++) {
			$token = $this->tokens[$index];
			if ($token->token === TokenType::OPERATOR) {
				if ($token->value === '<') {
					$token->token = TokenType::TYPE_PARAM_OPEN;
					$token->value = null;
				} else if ($token->value === '>') {
					$token->token = TokenType::TYPE_PARAM_CLOSE;
					$token->value = null;
				}
			} else if ($token->token === TokenType::IDENTIFIER) {
				$token->token = TokenType::TYPE_NAME;
				if ($this->isTypeRegisteredAtNesting($token->value, $this->curlyNesting + 1)) {
					throw new CompilerException("{$this->fileName}:{$token->line}:{$token->col}: Duplicate type declaration '{$token->value}'");
				}
				$this->registerTypeAtNesting($token->value, $this->curlyNesting + 1);
			}
		}
	}

	/**
	 * @param int $indexFrom
	 * @param int $indexTo non-inclusive
	 */
	protected function convertFunctionArgumentsTypeDefinitions(int $indexFrom, int $indexTo): void {
		for ($index = $indexFrom; $index < $indexTo; $index++) {
			$token = $this->tokens[$index];
			if ($token->token === TokenType::OPERATOR) {
				if ($token->value === '<') {
					$token->token = TokenType::TYPE_PARAM_OPEN;
					$token->value = null;
				} else if ($token->value === '>') {
					$token->token = TokenType::TYPE_PARAM_CLOSE;
					$token->value = null;
				} else if ($token->value === '>>') {
					$token->token = TokenType::TYPE_PARAM_CLOSE;
					$token->value = null;
					$this->insertTokenAfter(
						$index, new Token(
							TokenType::TYPE_PARAM_CLOSE,
							$token->line, $token->col + 1
						)
					);
					$index++;
					$indexTo++;
				}
			} else if ($token->token === TokenType::IDENTIFIER) {
				if ($this->isTypeRegisteredAtNesting($token->value, $this->curlyNesting + 1)) {
					$token->token = TokenType::TYPE_NAME;
				}
			}
		}
	}

	/**
	 * @param int $indexTo non-inclusive
	 * @return bool
	 */
	protected function checkTypeDefinition(int $indexTo): bool {
		// $this->tokens = ... `typeOrFnName` `<` `T` `,` `T2` `>`
		// $this->tokens = ... `typeOrFnName` `<` `Vec` `<` `T` `>>`
		// $this->tokens = ... `typeOrFnName` `<` `Vec` `<` `B` `<` `T` `>>` `>`

		$index = $indexTo - 1;
		$typeParamNesting = 0;
		$hasTypeParam = false;
		$needConvertTokens = false;

		while ($index > 1) {
			$token = $this->tokens[$index];
			if ($token->token === TokenType::COMMA) {
				if ($typeParamNesting === 0) {
					return false;
				}
			} else if (
				$token->token === TokenType::TYPE_PARAM_OPEN ||
				$token->isType(TokenType::OPERATOR, '<')
			) {
				$typeParamNesting--;
				if ($token->token === TokenType::OPERATOR) {
					$needConvertTokens = true;
				}
				if ($typeParamNesting === 0) {
					$prevToken = $this->tokens[$index - 1];
					if (
						$needConvertTokens && (
							$prevToken->token === TokenType::TYPE_NAME ||
							($hasTypeParam && $prevToken->token === TokenType::IDENTIFIER)
						)
					) {
						$this->convertTokensToTypeDefinition($index, $indexTo);
						return true;
					}
					return false;
				}
			} else if (
				$token->token === TokenType::TYPE_PARAM_CLOSE ||
				$token->isType(TokenType::OPERATOR, '>')
			) {
				$typeParamNesting++;
				if ($token->token === TokenType::OPERATOR) {
					$needConvertTokens = true;
				}
			} else if ($token->isType(TokenType::OPERATOR, '>>')) {
				$typeParamNesting += 2;
				$needConvertTokens = true;
			} else if ($token->token === TokenType::TYPE_NAME) {
				$hasTypeParam = true;
			} else if ($token->token === TokenType::IDENTIFIER) {
				$needConvertTokens = true;
			} else {
				return false;
			}
			$index--;
		}
		return false;
	}

	/**
	 * @param int $indexFrom
	 * @param int $indexTo non-inclusive
	 */
	protected function convertTokensToTypeDefinition(int $indexFrom, int $indexTo): void {
		for ($index = $indexFrom; $index < $indexTo; $index++) {
			$token = $this->tokens[$index];
			if ($token->token === TokenType::OPERATOR) {
				if ($token->value === '<') {
					$token->token = TokenType::TYPE_PARAM_OPEN;
					$token->value = null;
				} else if ($token->value === '>') {
					$token->token = TokenType::TYPE_PARAM_CLOSE;
					$token->value = null;
				} else if ($token->value === '>>') {
					$token->token = TokenType::TYPE_PARAM_CLOSE;
					$token->value = null;
					$this->insertTokenAfter(
						$index, new Token(
							TokenType::TYPE_PARAM_CLOSE,
							$token->line, $token->col + 1
						)
					);
					$index++;
					$indexTo++;
				}
			} else if ($token->token === TokenType::IDENTIFIER) {
				// disabled check to prevent error when generic function types were not registered yet
				/* if (!$this->isTypeRegisteredAtNesting($token->value, $this->curlyNesting + 1)) {
					throw new CompilerException("Type '{$token->value}' is not declared at line {$token->line}:{$token->col}");
				} */
				$token->token = TokenType::TYPE_NAME;
			}
		}
	}

	/** @var string */
	protected $fileName;

	/**
	 * @param string $code
	 * @param string $fileName
	 * @return Token[]
	 */
	public function tokenize(string $code, string $fileName): array {
		$this->fileName = $fileName;
		$this->init();
		$this->code = $code;
		$this->codeLength = strlen($code);

		while ($this->current < $this->codeLength) {
			if (
				$this->tryConsumeComment() ||
				$this->tryConsumeWhitespace()
			) {
				continue;
			}
			/** @var Token|null $token */
			$token = (
				$this->tryConsumeTypeDeclaration() ??
				$this->tryConsumeControl() ??
				$this->tryConsumeIntLiteral() ??
				$this->tryConsumeStringLiteral() ??
				$this->tryConsumeOperator() ??
				$this->tryConsumeWord()
			);
			if ($token === null) {
				$char = $this->peek(1);
				[$line, $col] = $this->getLineAndCol();
				throw new CompilerException("{$this->fileName}:{$line}:{$col}: Unexpected character '{$char}'");
			}
			$this->tokens[] = $token;
			if (
				$token->token === TokenType::OPERATOR &&
				($token->value === '>' || $token->value === '>>')
			) {
				$this->checkTypeDefinition(count($this->tokens));
			}
			if (
				$token->token === TokenType::COLON &&
				$this->isTokenTypeFromEnd(2, TokenType::PAREN_CLOSE)
			) {
				// function arguments closing. tokens array ends with:
				// ... `)` `:`
				$this->checkFunctionTypeParamsDefinition();
			}
		}

		return $this->tokens;
	}

}
