<?php

/**
 * Enum TokenType
 */
class TokenType {

	public const SEMICOLON = 1;
	public const COMMA = 2;
	public const COLON = 3;
	public const QUESTION = 4;
	public const CURLY_OPEN = 5;
	public const CURLY_CLOSE = 6;

	public const PAREN_OPEN = 7;
	public const PAREN_CLOSE = 8;

	public const SQUARE_OPEN = 9;
	public const SQUARE_CLOSE = 10;

	public const TYPE_PARAM_OPEN = 11;
	public const TYPE_PARAM_CLOSE = 12;

	// public const KEYWORD = 13;
	public const IDENTIFIER = 13;
	public const TYPE_NAME = 14;
	public const INT = 15;
	public const STRING = 16;
	// public const CHAR = 16;
	// public const FLOAT = 16;
	// public const DOUBLE = 16;

	public const OPERATOR = 17;


	public const KEYWORD_RET = 101;
	public const KEYWORD_FUNC = 102;
	public const KEYWORD_IF = 103;
	public const KEYWORD_ELSE = 104;
	public const KEYWORD_FOR = 105;
	public const KEYWORD_IN = 106;
	public const KEYWORD_WHILE = 107;
	public const KEYWORD_REPEAT = 108;
	public const KEYWORD_SWITCH = 109;
	public const KEYWORD_CASE = 110;
	public const KEYWORD_BREAK = 111;
	public const KEYWORD_CONTINUE = 112;

	public const KEYWORD_TRUE = 113;
	public const KEYWORD_FALSE = 114;
	public const KEYWORD_NULL = 115;

	public const KEYWORD_CLASS = 116;
	public const KEYWORD_INTERFACE = 117;
	public const KEYWORD_ENUM = 118;
	public const KEYWORD_TYPE = 119;

	public const KEYWORD_PUB = 120;
	public const KEYWORD_INLINE = 121;

	public const KEYWORD_STRUCT = 122;
	public const KEYWORD_EXTENDS = 123;
	public const KEYWORD_IMPLEMENTS = 124;
	public const KEYWORD_INSTANCEOF = 125;
	public const KEYWORD_THIS = 126;
	public const KEYWORD_NEW = 127;

	public const KEYWORD_TRY = 128;
	public const KEYWORD_CATCH = 129;
	public const KEYWORD_THROW = 130;

	public const KEYWORD_MUT = 131;
	public const KEYWORD_REF = 132;
	public const KEYWORD_LET = 133;

	public const KEYWORD_USE = 134;
	public const KEYWORD_OPERATOR = 135;
	public const KEYWORD_AS = 136;

	public const KEYWORD_IMPORT = 137;
	public const KEYWORD_EXPORT = 138;
	public const KEYWORD_FROM = 139;

}