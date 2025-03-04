<?php

/**
 * @property int $node
 * @property int $line
 * @property int $col
 */
interface Node { }

/**
 * @property Node[] $statements
 */
interface NodeProgram extends Node { }

/**
 * @property Node[] $statements
 */
interface NodeScope extends Node { }

/**
 * @property Node|NodeIdentifier $value
 */
interface NodeStmtExpression extends Node { }

/**
 * @property Node $value
 */
interface NodeStmtReturn extends Node { }

/**
 * @property Node $value
 */
interface NodeStmtIOPrint extends Node { }

/**
 * @property Node $condition
 * @property NodeScope $then
 * @property NodeScope|NodeStmtElseIf|null $else
 */
interface NodeStmtIf extends Node { }

/**
 * @property Node $condition
 * @property NodeScope $then
 * @property NodeScope|NodeStmtElseIf|null $else
 */
interface NodeStmtElseIf extends Node { }

/**
 * @property Node $condition
 * @property Node[] $statements
 */
interface NodeStmtWhileLoop extends Node { }

/**
 * @property NodeIdentifier|NodeDeclVariable $identifier
 * @property bool $exclusive
 * @property Node|NodeLiteralInt $from
 * @property Node|NodeLiteralInt $to
 * @property Node[] $statements
 */
interface NodeStmtForLoop extends Node { }

/**
 * @property NodeIdentifier $identifier
 * @property bool $mutable
 * @property NodeTypeExpression $type
 */
interface NodeDeclVariable extends Node { }

/**
 * @property NodeIdentifier $identifier
 * @property NodeTypeExpression $type
 */
interface NodeDeclType extends Node { }


/**
 * @property string $operator
 * @property NodeIdentifier|NodeDeclVariable $left
 * @property Node $right
 */
interface NodeExpressionAssignment extends Node { }

/**
 * @property string $operator
 * @property Node $left
 * @property Node $right
 */
interface NodeExpressionBinary extends Node { }

/**
 * @property string $operator
 * @property Node $value
 */
interface NodeExpressionUnary extends Node { }

/**
 * @property string $operator
 * @property bool $prefix
 * @property NodeIdentifier $value
 */
interface NodeExpressionUpdate extends Node { }


/**
 * @property int $value
 */
interface NodeLiteralInt extends Node { }

/**
 * @property int $value
 */
interface NodeLiteralBool extends Node { }

/**
 * @property string $name
 */
interface NodeIdentifier extends Node { }

/**
 * @property string $name
 */
interface NodeTypeExpression extends Node { }
