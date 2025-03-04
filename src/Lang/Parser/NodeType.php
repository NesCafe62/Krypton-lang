<?php

/**
 * Enum NodeType
 */
class NodeType {

	// STATEMENT = (STATEMENT_NOOP | STATEMENT_EXPRESSION | STATEMENT_RETURN | STATEMENT_IF | DECLARATION_VARIABLE | DECLARATION_FUNCTION)

	// EXPRESSION = (EXPRESSION_UNARY | EXPRESSION_BINARY | EXPRESSION_ASSIGNMENT | IDENTIFIER | LITERAL_INT | LITERAL_STRING | LITERAL_BOOL)

	// ELSE_STATEMENT = (SCOPE_STATEMENT | STATEMENT_ELSE_IF)

	public const PROGRAM = 1; // statements: STATEMENT[]

	public const STATEMENT_NOOP = 2;
	public const STATEMENT_RETURN = 3; // value: EXPRESSION
	public const STATEMENT_IF = 4; // condition: EXPRESSION, then: SCOPE_STATEMENT, ?else: ELSE_STATEMENT
	public const STATEMENT_ELSE_IF = 5; // condition: EXPRESSION, then: SCOPE_STATEMENT, ?else: ELSE_STATEMENT
	public const STATEMENT_EXPRESSION = 6;
	// public const STATEMENT_SWITCH = 2;
	public const STATEMENT_FOR_LOOP = 7; // identifier: (DECLARATION_VARIABLE | IDENTIFIER), from: EXPRESSION, to: EXPRESSION, exclusive: bool, statements: STATEMENT[]
	public const STATEMENT_WHILE_LOOP = 8; // condition: EXPRESSION, statements: STATEMENT[]
	// public const STATEMENT_DO_WHILE_LOOP = 2;
	// public const STATEMENT_USE = 2;
	public const STATEMENT_SCOPE = 9; // statements: STATEMENT[]

	public const DECLARATION_VARIABLE = 10; // identifier: IDENTIFIER, ?type: TYPE_EXPRESSION, ref: bool, mutable: bool
	public const DECLARATION_FUNCTION = 11; // identifier: IDENTIFIER, args: DEFINITION_ARGUMENT[], returnType: TYPE_EXPRESSION, body: STATEMENT[]
	// public const DECLARATION_OPERATOR = 2;
	// public const DECLARATION_CLASS = 2;
	// public const DECLARATION_INTERFACE = 2;
	public const DECLARATION_TYPE = 12; // identifier: IDENTIFIER, ?type: TYPE_EXPRESSION

	public const DEFINITION_ARGUMENT = 13; // type: TYPE_EXPRESSION, identifier: IDENTIFIER, defaultValue: (LITERAL | LITERAL_BOOL)
	// public const DEFINITION_LAMBDA = 2;
	// public const DEFINITION_PROPERTY = 2;

	public const CALL_FUNCTION = 14; // function: IDENTIFIER, args: EXPRESSION[]
	// public const CALL_CONSTRUCTOR = 2;

	public const EXPRESSION_UNARY = 15; // operator: string, value: EXPRESSION
	// public const EXPRESSION_UNARY_RIGHT = 15; // operator: string, left: EXPRESSION
	public const EXPRESSION_BINARY = 16; // operator: string, left: EXPRESSION, right: EXPRESSION
	public const EXPRESSION_ASSIGNMENT = 17; // operator: string, left: (DECLARATION_VARIABLE | IDENTIFIER), right: EXPRESSION
	public const EXPRESSION_UPDATE = 18; // operator: string, value: IDENTIFIER, prefix: bool
	// public const OPERATOR_PROPERTY = 2;
	// public const OPERATOR_INDEX = 2;

	// public const TYPE_CAST = 2;
	public const IDENTIFIER = 19; // name: string
	public const LITERAL_INT = 20; // type: ('i64' | 'i32' | 'i16' | 'i8' | 'u64' | 'u32' | 'u16' | 'u8') value: int
	public const LITERAL_BOOL = 21; // value: int
	public const LITERAL_STRING = 22; // value: string
	public const LITERAL_NULL = 23;

	// public const EXPRESSION = 2;
	public const TYPE_EXPRESSION = 24; // name: ('int' | 'string' | 'bool' | 'void') ?genericTypes: TYPE_EXPRESSION[]
	public const TYPES_LIST = 25;
	// public const TUPLE_EXPRESSION = 2;


	// todo: temporary io.print
	public const STATEMENT_IO_PRINT = 1000; // value: EXPRESSION

}