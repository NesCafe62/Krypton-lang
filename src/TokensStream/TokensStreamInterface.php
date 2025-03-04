<?php

interface TokensStreamInterface {

	function deleteTokens(int $from, int $length): void;

	/**
	 * @param int $from
	 * @param int $length
	 * @param Token[] $tokens
	 */
	function replaceTokens(int $from, int $length, array $tokens): void;

	/**
	 * @param int $at
	 * @param Token[] $tokens
	 */
	function insertTokens(int $at, array $tokens): void;

	function tokenAt(int $index): ?Token;

}
