<?php

class Token {

	/** @var int */
	public $token;

	/** @var string|null */
	public $value;

	/** @var int */
	public $line;

	/** @var int */
	public $col;

	public function __construct(int $tokenType, int $line, int $col, ?string $value = null) {
		$this->token = $tokenType;
		$this->value = $value;
		$this->line = $line;
		$this->col = $col;
	}

	public function isType(int $tokenType, string $value): bool {
		return (
			$this->token === $tokenType &&
			$this->value === $value
		);
	}

	public function isKeyword(): bool {
		return $this->token >= TokenType::KEYWORD_RET;
	}

}