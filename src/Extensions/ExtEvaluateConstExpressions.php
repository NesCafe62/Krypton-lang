<?php

class ExtEvaluateConstExpressions extends CompilerExtension {

	public $transformMode = TransformMode::AST;
	
	/** @var string */
	protected $fileName;
	
	public function __construct(string $fileName) {
		$this->fileName = $fileName;
	}

	/**
	 * @param Node|NodeExpressionBinary|NodeExpressionUnary|object $node
	 * @return null|Node|object
	 */
	public function transformASTNode(object $node): ?object {
		if ($node->node === NodeType::EXPRESSION_UNARY) {
			/** @var NodeLiteralInt $right */
			$right = $node->right;
			if ($right->node === NodeType::LITERAL_INT) {
				if ($node->operator === "-") {
					$value = -$right->value;
				} else if ($node->operator === "+") {
					$value = $right->value;
				} else if ($node->operator === "~") {
					// todo: consider sign bit behavior and size
					$value = ~$right->value;
				} else {
					throw new CompilerRuntimeException("Unary operator '{$node->operator}' is not implemented for integer literal");
				}
				return (object) [
					"node" => NodeType::LITERAL_INT,
					"value" => $value,
					"line" => $node->line, "col" => $node->col,
				];
			} else if ($right->node === NodeType::LITERAL_BOOL) {
				if ($node->operator === "!") {
					return (object) [
						"node" => NodeType::LITERAL_BOOL,
						"value" => (1 - $right->value),
						"line" => $node->line, "col" => $node->col,
					];
				} else {
					throw new CompilerRuntimeException("Unary operator '{$node->operator}' is not implemented for boolean literal");
				}
			}

		} else if ($node->node === NodeType::EXPRESSION_BINARY) {
			// todo: `... + 11 + 3` is not evaluated because parsed as `((... + 11) + 3)`
			/** @var NodeLiteralInt $left */
			$left = $node->left;
			/** @var NodeLiteralInt $right */
			$right = $node->right;
			if (
				$left->node === NodeType::LITERAL_INT &&
				$right->node === NodeType::LITERAL_INT
			) {
				// todo: maybe handle overflows
				if ($node->operator === "+") {
					$value = $left->value + $right->value;
				} else if ($node->operator === "-") {
					$value = $left->value - $right->value;
				} else if ($node->operator === "*") {
					$value = $left->value * $right->value;
				} else if ($node->operator === "/") {
					if ($right->value === 0) {
						throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Division by zero");
					}
					$value = intdiv($left->value, $right->value);
				} else if ($node->operator === "%") {
					if ($right->value === 0) {
						throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Division by zero");
					}
					$value = $left->value % $right->value;
				} else if ($node->operator === "&") {
					$value = $left->value & $right->value;
				} else if ($node->operator === "|") {
					$value = $left->value | $right->value;
				} else if ($node->operator === "^") {
					$value = $left->value ^ $right->value;
				} else {
					throw new CompilerRuntimeException("Operator '{$node->operator}' is not implemented for integer literals");
				}
				// todo `<<` `>>` `==` `!=` `>` `<` `<=` `>=`
				return (object) [
					"node" => NodeType::LITERAL_INT,
					"value" => $value,
					"line" => $left->line, "col" => $left->col,
				];
			}

			if (
				$left->node === NodeType::LITERAL_BOOL &&
				$right->node === NodeType::LITERAL_BOOL
			) {
				if ($node->operator === "&&") {
					$value = $left->value & $right->value;
				} else if ($node->operator === "||") {
					$value = $left->value | $right->value;
				} else {
					throw new CompilerRuntimeException("Operator '{$node->operator}' is not implemented for boolean literals");
				}
				// todo `==` `!=`
				return (object) [
					"node" => NodeType::LITERAL_BOOL,
					"value" => $value,
					"line" => $left->line, "col" => $left->col,
				];
			}
		}

		return $node;
	}

}