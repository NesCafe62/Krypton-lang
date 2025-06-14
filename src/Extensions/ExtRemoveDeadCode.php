<?php

class ExtRemoveDeadCode extends CompilerExtension {

	public $transformMode = TransformMode::AST_ROOT;

	/**
	 * @param NodeProgram|object $node
	 * @return NodeProgram
	 */
	public function transformASTRootNode(object $node): ?object {
		// todo: fail on assignment to non assignable expression (done?). currently such expressions are parsed as binaryExpression with `=` operator instead of assignmentExpression
		// and it will just remove such code with no warnings. which is not right
		// (x + 2) = 5;
		$statements = [];
		foreach ($node->statements as $statement) {
			$this->removeDeadCode($statement, $statements);
		}
		return (object) [
			'node' => NodeType::PROGRAM,
			'statements' => $statements,
			'line' => $node->line, 'col' => $node->col,
		];
	}

	/**
	 * @param Node|object $node
	 * @param Node[] $statements
	 */
	protected function removeDeadCode(object $node, array &$statements): void {
		if ($node->node === NodeType::STATEMENT_SCOPE) {
			$scopeStatements = [];
			foreach ($node->statements as $statement) {
				$this->removeDeadCode($statement, $scopeStatements);
			}
			$statements[] = (object) [
				'node' => NodeType::STATEMENT_SCOPE,
				'statements' => $scopeStatements,
				'line' => $node->line, 'col' => $node->col,
			];
			return;
		}

		if ($node->node === NodeType::STATEMENT_WHILE_LOOP) {
			$loopStatements = [];
			foreach ($node->statements as $statement) {
				$this->removeDeadCode($statement, $loopStatements);
			}
			$statements[] = (object) [
				'node' => NodeType::STATEMENT_WHILE_LOOP,
				'condition' => $node->condition,
				'statements' => $loopStatements,
				'line' => $node->line, 'col' => $node->col,
			];
			return;
		}

		if ($node->node === NodeType::STATEMENT_FOR_LOOP) {
			$loopStatements = [];
			foreach ($node->statements as $statement) {
				$this->removeDeadCode($statement, $loopStatements);
			}
			$statements[] = (object) [
				'node' => NodeType::STATEMENT_FOR_LOOP,
				'exclusive' => $node->exclusive,
				'identifier' => $node->identifier,
				'from' => $node->from,
				'to' => $node->to,
				'statements' => $loopStatements,
				'line' => $node->line, 'col' => $node->col,
			];
			return;
		}

		if (
			$node->node === NodeType::DECLARATION_VARIABLE ||
			$node->node === NodeType::DECLARATION_TYPE ||
			$node->node === NodeType::EXPRESSION_ASSIGNMENT ||
			$node->node === NodeType::EXPRESSION_UPDATE ||
			$node->node === NodeType::STATEMENT_IF ||
			$node->node === NodeType::STATEMENT_RETURN ||

			$node->node === NodeType::STATEMENT_IO_PRINT // todo: temporary io.print
		) {
			$statements[] = $node;
			return;
		}

		if ($node->node === NodeType::EXPRESSION_BINARY) {
			$this->removeDeadCode($node->left, $statements);
			$this->removeDeadCode($node->right, $statements);
			return;
		}
		if ($node->node === NodeType::STATEMENT_EXPRESSION) {
			$this->removeDeadCode($node->value, $statements);
			return;
		}
		if ($node->node === NodeType::EXPRESSION_UNARY) {
			$this->removeDeadCode($node->right, $statements);
			return;
		}
	}

}