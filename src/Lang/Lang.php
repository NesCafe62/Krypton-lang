<?php

class Lang {

	/* public static $grammar = [
		NodeType::STATEMENT_NOOP => [
			'seq' => [['token', TokenType::SEMICOLON]]
		],
		NodeType::TYPE_EXPRESSION => [
			'seqOr' => [
				[['token', TokenType::TYPE_NAME]],
				[['token', TokenType::TYPE_NAME], ['token', TokenType::TYPE_PARAM_OPEN], ['token', NodeType::TYPE_EXPRESSION], ['token', TokenType::TYPE_PARAM_CLOSE]],
				[['token', TokenType::TYPE_NAME], ['token', TokenType::TYPE_PARAM_OPEN], ['token', NodeType::TYPES_LIST], ['token', TokenType::TYPE_PARAM_CLOSE]]
			]
		],
		NodeType::TYPES_LIST => [
			'seqOr' => [
				[['node', NodeType::TYPE_EXPRESSION], ['token', TokenType::COMMA], ['node', NodeType::TYPE_EXPRESSION]],
				[['node', NodeType::TYPE_EXPRESSION], ['token', TokenType::COMMA], ['node', NodeType::TYPES_LIST]],
			]
		],
		NodeType::DECLARATION_VARIABLE => [
			'seq' => [['node', NodeType::TYPE_EXPRESSION], ['token', TokenType::IDENTIFIER], ['token', TokenType::SEMICOLON]]
		],
		NodeType::STATEMENT_RETURN => [
			'seq' => [['token', TokenType::KEYWORD, 'ret'], ['token', TokenType::INT], ['token', TokenType::SEMICOLON]]
		]
	]; */

	public const ASSOC_LEFT = 0;
	public const ASSOC_RIGHT = 1;

	public static $keywords = [
		"ret" => TokenType::KEYWORD_RET,
		"func" => TokenType::KEYWORD_FUNC,
		"if" => TokenType::KEYWORD_IF,
		"else" => TokenType::KEYWORD_ELSE,
		"for" => TokenType::KEYWORD_FOR,
		"in" => TokenType::KEYWORD_IN,
		"while" => TokenType::KEYWORD_WHILE,
		"repeat" => TokenType::KEYWORD_REPEAT,
		"switch" => TokenType::KEYWORD_SWITCH,
		"case" => TokenType::KEYWORD_CASE,
		"break" => TokenType::KEYWORD_BREAK,
		"continue" => TokenType::KEYWORD_CONTINUE,
		// match

		"true" => TokenType::KEYWORD_TRUE,
		"false" => TokenType::KEYWORD_FALSE,
		"null" => TokenType::KEYWORD_NULL,

		// struct
		"class" => TokenType::KEYWORD_CLASS,
		"interface" => TokenType::KEYWORD_INTERFACE,
		"enum" => TokenType::KEYWORD_ENUM,
		"type" => TokenType::KEYWORD_TYPE,
		// module
		// namespace

		"pub" => TokenType::KEYWORD_PUB,
		"inline" => TokenType::KEYWORD_INLINE,

		"struct" => TokenType::KEYWORD_STRUCT,
		"extends" => TokenType::KEYWORD_EXTENDS,
		"implements" => TokenType::KEYWORD_IMPLEMENTS,
		"instanceof" => TokenType::KEYWORD_INSTANCEOF,
		"this" => TokenType::KEYWORD_THIS,
		"new" => TokenType::KEYWORD_NEW,

		"try" => TokenType::KEYWORD_TRY,
		"catch" => TokenType::KEYWORD_CATCH,
		"throw" => TokenType::KEYWORD_THROW,

		"mut" => TokenType::KEYWORD_MUT,
		"ref" => TokenType::KEYWORD_REF,
		"let" => TokenType::KEYWORD_LET,

		"use" => TokenType::KEYWORD_USE,
		"operator" => TokenType::KEYWORD_OPERATOR,
		"as" => TokenType::KEYWORD_AS,

		"import" => TokenType::KEYWORD_IMPORT,
		"export" => TokenType::KEYWORD_EXPORT,
		"from" => TokenType::KEYWORD_FROM,
	];

	public static $typeDeclarationKeywords = [
		TokenType::KEYWORD_CLASS => true,
		TokenType::KEYWORD_INTERFACE => true,
		TokenType::KEYWORD_ENUM => true,
		TokenType::KEYWORD_TYPE => true,
	];

	public const TYPE_INFER = "Infer";


	// alias  | namespace | asmType | size  | internalType
	// bool   | Bool      | i8      | 8 (1) | u8
	// char   | Char      | i8      | 8     | u8
	// int8   | Int8      | i8      | 8     | i8
	// byte   | UInt8     | i8      | 8     | u8
	// short  | Int16     | i16     | 16    | i16
	// ushort | UInt16    | i16     | 16    | u16
	// uint   | UInt32    | i32     | 32    | u32
	// int    | Int32     | i32     | 32    | i32
	// long   | Int64     | i64     | 64    | i64
	// ulong  | UInt64    | i64     | 64    | u64
	// float  | Float32   | f32     | 32    | f32
	// double | Float64   | f64     | 64    | f64

	public static $types = [
		// typeName => namespace
		"int8" => "Int8",
		"int16" => "Int16",
		"int32" => "Int32",
		"int64" => "Int64",

		"uint8" => "UInt8",
		"uint16" => "UInt16",
		"uint32" => "UInt32",
		"uint64" => "UInt64",

		"float32" => "Float32",
		"float64" => "Float64",

		"byte" => "UInt8",
		"int" => "Int32",
		"uint" => "UInt32",
		// "long" => "Int64",
		// "ulong" => "UInt64",
		"float" => "Float32",
		"char" => "Int32", // or UInt8 ?


		"bool" => "Bool",

		"void" => null,

		"Array" => "Array",
		"List" => "List",
		// "object" => "Object",
		// "Map" => "Map",
		// "WeakMap" => "WeakMap",
		// "Set" => "Set",
		// "Ref" => "Ref",
		// "Opt" => "Opt",
		// "Func" => "Func",
		// "Tuple" => null,
		// "Enumerable" => "Enumerable",

		// "decimal" => "Decimal",
	];

	/* public static $_types = [
		// RefObj
		// RefFn
		// RefEq
		// RefStructInst<>
		// RefClassInst<>
		// RefArray
		// RefString

		// 'String::', // ref
		// 'Int::',
		// 'Boolean::',
		// 'Array<T>::', // ref

		// 'List<T>::', // ref
		// 'Map<K,T>::', // ref
		// 'WeakMap<K,T>::', // ref
		// 'Set<T>::', // ref

		// 'Ref<T>::',
		// 'Opt<T>::',
		// 'Fn<...>::',
		// 'Tuple<...>::',
		// 'Enumerable<T>::',
	]; */

	public static $operators = [
		// op => [precedence, assoc]

		"..=" => [5 /* ? */, self::ASSOC_LEFT], // range inclusive
		".." => [5 /* ? */, self::ASSOC_LEFT], // range exclusive
		// "**" => [13],
		// "|>" => [_],
		// "??" => [2],
		// "?." => [17],
		// "?:" => [2],

		"+=" => [1, self::ASSOC_LEFT],
		"-=" => [1, self::ASSOC_LEFT],
		"*=" => [1, self::ASSOC_LEFT],
		// "/=" => [1],
		// "%=" => [1],
		"|=" => [1, self::ASSOC_LEFT],
		"&=" => [1, self::ASSOC_LEFT],
		"^=" => [1, self::ASSOC_LEFT],
		/* "**=" => [1],
		">>=" => [1],
		"<<=" => [1],
		"&&=" => [1],
		"||=" => [1],
		"??=" => [1],

		// unary right "++" => [15],
		// unary right "--" => [15],

		// "new" => [16]
		// fn call "()" => [17]

		// unary left "+" => [14],
		// unary left "-" => [14], */
		"++" => [14, self::ASSOC_LEFT],
		"--" => [14, self::ASSOC_LEFT],

		"+" => [11, self::ASSOC_LEFT],
		"-" => [11, self::ASSOC_LEFT],
		"*" => [12, self::ASSOC_LEFT],
		"/" => [12, self::ASSOC_LEFT],
		"%" => [12, self::ASSOC_LEFT],

		"&&" => [4, self::ASSOC_LEFT],
		"||" => [3, self::ASSOC_LEFT],

		"|" => [7, self::ASSOC_LEFT], // 5 in JS
		"^" => [8, self::ASSOC_LEFT], // 6 in JS
		"&" => [9, self::ASSOC_LEFT], // 7 in JS
		">>" => [10, self::ASSOC_LEFT],
		"<<" => [10, self::ASSOC_LEFT],

		// "instanceof" => [9] // 6
		// "typeof" => [14]
		// "( )" => [18]

		"==" => [5, self::ASSOC_LEFT], // 8 in JS
		"!=" => [5, self::ASSOC_LEFT], // 8 in JS
		"<=" => [6, self::ASSOC_LEFT], // 9 in JS
		">=" => [6, self::ASSOC_LEFT], // 9 in JS
		">" => [6, self::ASSOC_LEFT], // 9 in JS
		"<" => [6, self::ASSOC_LEFT], // 9 in JS

		// unary
		"!" => [14, self::ASSOC_LEFT],
		"~" => [6, self::ASSOC_LEFT], // 9 in JS

		"." => [17, self::ASSOC_LEFT],
		// "::" => [17],
		// "=>" => [_], // return alias
		"=" => [1, self::ASSOC_LEFT],
	];

	public static $controlTokens = [
		TokenType::SEMICOLON => true,
		TokenType::COMMA => true,
		TokenType::COLON => true,
		TokenType::QUESTION => true,
		TokenType::CURLY_OPEN => true,
		TokenType::CURLY_CLOSE => true,
		TokenType::PAREN_OPEN => true,
		TokenType::PAREN_CLOSE => true,
		TokenType::SQUARE_OPEN => true,
		TokenType::SQUARE_CLOSE => true,
	];

	public static $tokens = [
		TokenType::SEMICOLON => ";",
		TokenType::COMMA => ",",
		TokenType::COLON => ":",
		TokenType::QUESTION => "?",
		TokenType::CURLY_OPEN => "{",
		TokenType::CURLY_CLOSE => "}",
		TokenType::PAREN_OPEN => "(",
		TokenType::PAREN_CLOSE => ")",
		TokenType::SQUARE_OPEN => "[",
		TokenType::SQUARE_CLOSE => "]",

		TokenType::TYPE_PARAM_OPEN => "TYPE_PARAM_OPEN",
		TokenType::TYPE_PARAM_CLOSE => "TYPE_PARAM_CLOSE",

		// TokenType::KEYWORD => "KEYWORD",
		TokenType::IDENTIFIER => "IDENTIFIER",
		TokenType::TYPE_NAME => "TYPE_NAME",
		TokenType::INT => "INT",
		TokenType::STRING => "STRING",
		// TokenType::CHAR => "CHAR",
		// TokenType::FLOAT => "FLOAT",
		// TokenType::DOUBLE => "DOUBLE",
		TokenType::OPERATOR => "OPERATOR",
		
		
		TokenType::KEYWORD_RET => "KEYWORD_RET",
		TokenType::KEYWORD_FUNC => "KEYWORD_FUNC",
		TokenType::KEYWORD_IF => "KEYWORD_IF",
		TokenType::KEYWORD_ELSE => "KEYWORD_ELSE",
		TokenType::KEYWORD_FOR => "KEYWORD_FOR",
		TokenType::KEYWORD_IN => "KEYWORD_IN",
		TokenType::KEYWORD_WHILE => "KEYWORD_WHILE",
		TokenType::KEYWORD_REPEAT => "KEYWORD_REPEAT",
		TokenType::KEYWORD_SWITCH => "KEYWORD_SWITCH",
		TokenType::KEYWORD_CASE => "KEYWORD_CASE",
		TokenType::KEYWORD_BREAK => "KEYWORD_BREAK",
		TokenType::KEYWORD_CONTINUE => "KEYWORD_CONTINUE",
	
		TokenType::KEYWORD_TRUE => "KEYWORD_TRUE",
		TokenType::KEYWORD_FALSE => "KEYWORD_FALSE",
		TokenType::KEYWORD_NULL => "KEYWORD_NULL",
	
		TokenType::KEYWORD_CLASS => "KEYWORD_CLASS",
		TokenType::KEYWORD_INTERFACE => "KEYWORD_INTERFACE",
		TokenType::KEYWORD_ENUM => "KEYWORD_ENUM",
		TokenType::KEYWORD_TYPE => "KEYWORD_TYPE",
	
		TokenType::KEYWORD_PUB => "KEYWORD_PUB",
		TokenType::KEYWORD_INLINE => "KEYWORD_INLINE",
	
		TokenType::KEYWORD_STRUCT => "KEYWORD_STRUCT",
		TokenType::KEYWORD_EXTENDS => "KEYWORD_EXTENDS",
		TokenType::KEYWORD_IMPLEMENTS => "KEYWORD_IMPLEMENTS",
		TokenType::KEYWORD_INSTANCEOF => "KEYWORD_INSTANCEOF",
		TokenType::KEYWORD_THIS => "KEYWORD_THIS",
		TokenType::KEYWORD_NEW => "KEYWORD_NEW",
	
		TokenType::KEYWORD_TRY => "KEYWORD_TRY",
		TokenType::KEYWORD_CATCH => "KEYWORD_CATCH",
		TokenType::KEYWORD_THROW => "KEYWORD_THROW",
	
		TokenType::KEYWORD_MUT => "KEYWORD_MUT",
		TokenType::KEYWORD_REF => "KEYWORD_REF",
		TokenType::KEYWORD_LET => "KEYWORD_LET",
	
		TokenType::KEYWORD_USE => "KEYWORD_USE",
		TokenType::KEYWORD_OPERATOR => "KEYWORD_OPERATOR",
		TokenType::KEYWORD_AS => "KEYWORD_AS",
	
		TokenType::KEYWORD_IMPORT => "KEYWORD_IMPORT",
		TokenType::KEYWORD_EXPORT => "KEYWORD_EXPORT",
		TokenType::KEYWORD_FROM => "KEYWORD_FROM",
	];

	public static $nodes = [
		NodeType::PROGRAM => "Program",

		NodeType::STATEMENT_NOOP => "NoOpStatement",
		NodeType::STATEMENT_RETURN => "ReturnStatement",
		NodeType::STATEMENT_IF => "IfStatement",
		NodeType::STATEMENT_ELSE_IF => "ElseIfStatement",
		NodeType::STATEMENT_EXPRESSION => "ExpressionStatement",
		// NodeType::STATEMENT_SWITCH => "SwitchStatement",
		NodeType::STATEMENT_FOR_LOOP => "ForLoopStatement",
		NodeType::STATEMENT_WHILE_LOOP => "WhileLoopStatement",
		// NodeType::STATEMENT_REPEAT_WHILE_LOOP => "RepeatWhileLoopStatement",
		// NodeType::STATEMENT_USE => "UseStatement",
		NodeType::STATEMENT_SCOPE => "ScopeStatement",

		NodeType::DECLARATION_VARIABLE => "VariableDeclaration",
		NodeType::DECLARATION_FUNCTION => "FunctionDeclaration",
		// NodeType::DECLARATION_OPERATOR => "OperatorDeclaration",
		// NodeType::DECLARATION_CLASS => "ClassDeclaration",
		// NodeType::DECLARATION_INTERFACE => "InterfaceDeclaration",
		NodeType::DECLARATION_TYPE => "TypeDeclaration",

		NodeType::DEFINITION_ARGUMENT => "ArgumentDefinition",
		// NodeType::DEFINITION_LAMBDA => "LambdaDefinition",
		// NodeType::DEFINITION_PROPERTY => "PropertyDefinition",

		NodeType::CALL_FUNCTION => "FunctionCall",
		// NodeType::CALL_CONSTRUCTOR => "ConstructorCall",

		NodeType::EXPRESSION_UNARY => "UnaryExpression",
		// NodeType::EXPRESSION_UNARY_RIGHT => "UnaryExpressionRight",
		NodeType::EXPRESSION_BINARY => "BinaryExpression",
		NodeType::EXPRESSION_ASSIGNMENT => "AssignmentExpression",
		NodeType::EXPRESSION_UPDATE => "UpdateExpression",
		// NodeType::OPERATOR_PROPERTY => "OperatorProperty",
		// NodeType::OPERATOR_INDEX => "OperatorIndex",

		// NodeType::TYPE_CAST => "TypeCast",
		NodeType::IDENTIFIER => "Identifier",

		NodeType::LITERAL_INT => "LiteralInt",
		NodeType::LITERAL_STRING => "LiteralString",
		NodeType::LITERAL_BOOL => "LiteralBool",
		NodeType::LITERAL_NULL => "LiteralNull",

		// NodeType::EXPRESSION => "Expression",
		NodeType::TYPE_EXPRESSION => "TypeExpression",
		// NodeType::TYPES_LIST => "TypesList",


		NodeType::STATEMENT_IO_PRINT => "IOPrintStatement", // todo: temporary io.print
	];

	public static function isControlToken(int $tokenType): bool {
		return isset(self::$controlTokens[$tokenType]);
	}

	public static function displayToken(int $tokenType, ?string $value = null): string {
		$display = self::$tokens[$tokenType] ?? "UNKNOWN_TOKEN[{$tokenType}]";
		if (isset(self::$controlTokens[$tokenType])) {
			return "`{$display}`";
		}
		if ($value !== null) {
			return "{$display}:`{$value}`";
		}
		return $display;
	}

	public static function getOperatorInfo(string $value): array {
		if (!isset(Lang::$operators[$value])) {
			throw new CompilerRuntimeException("Token `{$value}` is not an operator");
		}
		return Lang::$operators[$value];
	}

	/**
	 * @param Node|object $node
	 * @return string
	 */
	public static function getNodeTypeName(object $node): string {
		return self::$nodes[$node->node] ?? "UNKNOWN_NODE:{$node->node}";
	}

	/**
	 * @param Node|object $node
	 * @param int $indentLevel
	 * @return string
	 */
	public static function displayNode(object $node, int $indentLevel = 0): string {
		$nodeType = self::getNodeTypeName($node);
		$offset = str_repeat("    ", $indentLevel);
		switch ($node->node) {
			case NodeType::PROGRAM:
			case NodeType::STATEMENT_SCOPE:
				$display = "{$nodeType} [\n";
				foreach ($node->statements as $statement) {
					$display .= $offset."    ".self::displayNode($statement, $indentLevel + 1);
				}
				$display .= $offset."]\n";
				break;
			case NodeType::IDENTIFIER:
				$display = "{$nodeType} { name: `{$node->name}` }\n";
				break;
			case NodeType::TYPE_EXPRESSION:
				$display = "{$nodeType} {\n";
				$display .= $offset."    name: `{$node->name}`\n";
				$display .= $offset."}\n";
				break;
			case NodeType::LITERAL_INT:
			case NodeType::LITERAL_BOOL:
			case NodeType::LITERAL_STRING:
				$display = "{$nodeType} { value: `{$node->value}` }\n";
				break;
			case NodeType::EXPRESSION_UNARY:
				$display = "{$nodeType} {\n";
				$display .= $offset."    operator: `{$node->operator}`\n";
				$display .= $offset."    value: ".self::displayNode($node->value, $indentLevel + 1);
				$display .= $offset."}\n";
				break;
			case NodeType::EXPRESSION_UPDATE:
				$display = "{$nodeType} {\n";
				$display .= $offset."    operator: `{$node->operator}`\n";
				$display .= $offset."    prefix: ".($node->prefix ? "true\n" : "false\n");
				$display .= $offset."    value: ".self::displayNode($node->value, $indentLevel + 1);
				$display .= $offset."}\n";
				break;
			case NodeType::STATEMENT_WHILE_LOOP:
				$display = "{$nodeType} {\n";
				$display .= $offset."    condition: ".self::displayNode($node->condition, $indentLevel + 1);
				$display .= $offset."    statements: [\n";
				foreach ($node->statements as $statement) {
					$display .= $offset."        ".self::displayNode($statement, $indentLevel + 2);
				}
				$display .= $offset."    ]\n";
				$display .= $offset."}\n";
				break;
			case NodeType::STATEMENT_FOR_LOOP:
				$display = "{$nodeType} {\n";
				$display .= $offset."    identifier: ".self::displayNode($node->identifier, $indentLevel + 1);
				$display .= $offset."    exclusive: ".($node->exclusive ? "true\n" : "false\n");
				$display .= $offset."    from: ".self::displayNode($node->from, $indentLevel + 1);
				$display .= $offset."    to: ".self::displayNode($node->to, $indentLevel + 1);
				$display .= $offset."    statements: [\n";
				foreach ($node->statements as $statement) {
					$display .= $offset."        ".self::displayNode($statement, $indentLevel + 2);
				}
				$display .= $offset."    ]\n";
				$display .= $offset."}\n";
				break;
			case NodeType::STATEMENT_IF:
			case NodeType::STATEMENT_ELSE_IF:
				$display = "{$nodeType} {\n";
				$display .= $offset."    condition: ".self::displayNode($node->condition, $indentLevel + 1);
				$display .= $offset."    then: ".self::displayNode($node->then, $indentLevel + 1);
				if ($node->else !== null) {
					$display .= $offset."    else: ".self::displayNode($node->else, $indentLevel + 1);
				}
				$display .= $offset."}\n";
				break;
			case NodeType::EXPRESSION_ASSIGNMENT:
			case NodeType::EXPRESSION_BINARY:
				$display = "{$nodeType} {\n";
				$display .= $offset."    operator: `{$node->operator}`\n";
				$display .= $offset."    left: ".self::displayNode($node->left, $indentLevel + 1);
				$display .= $offset."    right: ".self::displayNode($node->right, $indentLevel + 1);
				$display .= $offset."}\n";
				break;
			case NodeType::DECLARATION_TYPE:
				$display = "{$nodeType} {\n";
				$display .= $offset."    identifier: ".self::displayNode($node->identifier, $indentLevel + 1);
				$display .= $offset."    type: ".self::displayNode($node->type, $indentLevel + 1);
				$display .= $offset."}\n";
				break;
			case NodeType::DECLARATION_VARIABLE:
				$display = "{$nodeType} {\n";
				$display .= $offset."    identifier: ".self::displayNode($node->identifier, $indentLevel + 1);
				// ref: ...
				$display .= $offset."    mutable: ".($node->mutable ? "true\n" : "false\n");
				if ($node->type === null) {
					$display .= $offset."    type: Infer\n";
				} else {
					$display .= $offset."    type: ".self::displayNode($node->type, $indentLevel + 1);
				}
				$display .= $offset."}\n";
				break;
			case NodeType::STATEMENT_EXPRESSION:
			case NodeType::STATEMENT_RETURN:
			case NodeType::STATEMENT_IO_PRINT:
				$display = "{$nodeType} {\n";
				$display .= $offset."    value: ".self::displayNode($node->value, $indentLevel + 1);
				$display .= $offset."}\n";
				break;
			default:
				$display = "{$nodeType}\n";
				break;
		}
		return $display;
	}

}



