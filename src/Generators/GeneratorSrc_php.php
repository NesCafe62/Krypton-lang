<?php

class GeneratorSrc_php implements GeneratorInterface {

	/** @var int */
	protected $scopeNesting;

	/** @var array */
	protected $usedBuiltIns = [];

	protected const ROOT_SCOPE_NESTING = 1;

	protected function init(): void {
		$this->scopeNesting = self::ROOT_SCOPE_NESTING;
		$this->usedBuiltIns = [];
	}

	protected function emit(string $code): void {
		echo str_repeat("    ", $this->scopeNesting - 1) . $code.PHP_EOL;
	}

	protected function unwrap(string $code): string {
		if ($code[0] === "(" && $code[strlen($code) - 1] === ")") {
			return substr($code, 1, strlen($code) - 2);
		}
		return $code;
	}

	/**
	 * @param NodeLiteralInt|object $node
	 * @return string
	 */
	protected function generateLiteralInt(object $node): string {
		return (string) $node->value;
	}

	/**
	 * @param NodeLiteralBool|object $node
	 * @return string
	 */
	protected function generateLiteralBool(object $node): string {
		return $node->value === 0 ? "false" : "true";
	}

	/**
	 * @param NodeIdentifier|object $node
	 * @return string
	 */
	protected function generateExpressionIdentifier(object $node): string {
		$identifier = $node->name;
		/* if (!isset($this->identifiers[$identifier])) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' is not declared");
		}
		$data = $this->identifiers[$identifier];
		if ($data->initScope === 0) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Usage of uninitialized variable '{$identifier}'");
		} */
		return "$" . $identifier;
	}

	/**
	 * @param NodeExpressionBinary|object $node
	 * @param bool $allocAsCmpFlag
	 * @return string
	 */
	protected function generateExpressionBinary(object $node, bool $allocAsCmpFlag = false): string {
		$allocLeft = $this->generateExpression($node->left, true);
		$allocRight = $this->generateExpression($node->right, true);

		$operator = $node->operator;
		if ($node->operator === "==") {
			$operator = "===";
		} else if ($node->operator === "!=") {
			$operator = "!==";
		}

		return "(".$allocLeft." ".$operator." ".$allocRight.")";

		// throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Operator '{$node->operator}' is not implemented");
	}

	/**
	 * @param NodeExpressionUnary|object $node
	 * @param bool $isResultUsed
	 * @return string
	 */
	protected function generateExpressionUnary(object $node, bool $isResultUsed): string {
		$alloc = $this->generateExpression($node->value, $isResultUsed);
		if ($node->operator === "+") {
			// do nothing
			return $alloc;
		}
		if ($node->operator === "!") {
			return "!".$alloc;
		}
		return "(".$node->operator.$alloc.")";

		// throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Operator '{$node->operator}' is not implemented");
	}

	/**
	 * @param NodeExpressionUpdate|object $node
	 * @param bool $isResultUsed
	 * @return string
	 */
	protected function generateExpressionUpdate(object $node, bool $isResultUsed): string {
		if ($node->operator === "++") {
			$identifier = $this->generateExpressionIdentifier($node->value);
			if ($node->prefix) {
				return "++".$identifier;
			} else {
				return $identifier."++";
			}

		} else if ($node->operator === "--") {
			$identifier = $this->generateExpressionIdentifier($node->value);
			if ($node->prefix) {
				return "--".$identifier;
			} else {
				return $identifier."--";
			}

		} else {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Update operator '{$node->operator}' is not implemented");
		}
	}

	/**
	 * @param NodeIdentifier|object $node
	 * @param int $step
	 * @return string
	 */
	protected function generateIncrementVariable(object $node, int $step): string {
		$identifier = $this->generateExpressionIdentifier($node);

		if ($step === 1) {
			return $identifier."++";
		}
		if ($step === -1) {
			return $identifier."--";
		}

		/* if (!isset($this->identifiers[$identifier])) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' is not declared");
		}
		$data = $this->identifiers[$identifier];

		if (!$data->mutable) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Can't modify an immutable variable '{$identifier}'");
		}
		if ($data->initScope === 0) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Usage of uninitialized variable '{$identifier}'");
		} */

		throw new CompilerRuntimeException("Increment step '{$step}' should be '1' or '-1'");
	}

	/**
	 * @param NodeExpressionAssignment|object $node
	 * @return string
	 */
	protected function generateExpressionAssignment(object $node): string {
		if ($node->left->node === NodeType::DECLARATION_VARIABLE) {
			// $this->generateDeclVariable($node->left);
			$identifier = $node->left->identifier->name;
		} else {
			$identifier = $node->left->name;
		}

		$allocRight = $this->generateExpression($node->right, true);

		/* if (!isset($this->identifiers[$identifier])) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' is not declared");
		}
		$data = $this->identifiers[$identifier]; */

		// if ($node->operator === '=') {
			/* $allocLeft = $data->alloc;

			if ($data->initScope === 0) {
				$data->initScope = $this->scopeNesting;
			} */

			// return $identifier.' = '.$allocRight;
		// }

		return "($".$identifier." ".$node->operator." ".$this->unwrap($allocRight).")";

		/* if (!$data->mutable) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Can't modify an immutable variable '{$identifier}'");
		}
		if ($data->initScope === 0) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Usage of uninitialized variable '{$identifier}'");
		}

		$allocLeft = $data->alloc; */

		// $this->emitComment("{$identifier} {$node->operator} (...)");

		// throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Assignment operator '{$node->operator}' is not implemented");
		// throw new CompilerRuntimeException("{$this->fileName}:{$node->line}:{$node->col}: Unknown assignment operator '{$node->operator}'");
	}

	/**
	 * @param NodeStmtExpression|object $node
	 * @param bool $isResultUsed
	 * @param bool $allocAsCmpFlag
	 * @return string
	 */
	protected function generateExpression(object $node, bool $isResultUsed, bool $allocAsCmpFlag = false): string {
		if ($node->node === NodeType::EXPRESSION_ASSIGNMENT) {
			return $this->generateExpressionAssignment($node);
		}
		if ($node->node === NodeType::EXPRESSION_BINARY) {
			return $this->generateExpressionBinary($node, $allocAsCmpFlag);
		}
		if ($node->node === NodeType::EXPRESSION_UNARY) {
			return $this->generateExpressionUnary($node, $isResultUsed);
		}
		if ($node->node === NodeType::EXPRESSION_UPDATE) {
			return $this->generateExpressionUpdate($node, $isResultUsed);
		}
		if ($node->node === NodeType::LITERAL_INT) {
			return $this->generateLiteralInt($node);
		}
		if ($node->node === NodeType::LITERAL_BOOL) {
			return $this->generateLiteralBool($node);
		}
		if ($node->node === NodeType::IDENTIFIER) {
			return $this->generateExpressionIdentifier($node);
		}
		$nodeType = Lang::getNodeTypeName($node);
		throw new CompilerRuntimeException("Wrong node type: {$nodeType}");
	}

	/**
	 * @param NodeStmtReturn|object $node
	 */
	protected function generateStmtReturn(object $node): void {
		// throw new CompilerRuntimeException('Statement return is not implemented!');

		/* $alloc = $this->generateExpression($node->value, true);
		$this->deallocateIfRegister($alloc);
		$this->emitComment("ret (...)");
		$regA = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => self::REGISTER_A,
			'size' => self::SIZE_64,
		];
		$this->emit('mov', $regA, $alloc);
		$this->emit('ret'); */

		$alloc = $this->generateExpression($node->value, true);

		$this->emit("return ".$this->unwrap($alloc).";");
	}

	/**
	 * @param NodeStmtIOPrint|object $node
	 */
	protected function generateStmtIOPrint(object $node): void {
		$this->usedBuiltIns["io.print()"] = true;
		$alloc = $this->generateExpression($node->value, true);

		/* $this->deallocateIfRegister($alloc);
		$this->emitComment("io.print (...)");
		$allocLeft = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => self::REGISTER_D,
			'size' => $alloc->size,
		];
		$this->emit('mov', $allocLeft, $alloc);
		$regC = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => self::REGISTER_C,
			'size' => self::SIZE_64,
		];
		$this->emitLea($regC, 'format_i32');
		// -- $this->pop('rdx');
		// -- echo "    pop     rdx\n";
		// -- echo "    mov     rdx, 5\n";
		$this->emitCall('printf'); */

		$this->emit("io::print(".$this->unwrap($alloc).");");
	}

	/**
	 * @param NodeStmtIf|NodeStmtElseIf|object $node
	 * @param string|null $labelEndIf
	 */
	protected function generateStmtIf(object $node, ?string $labelEndIf = null): void {
		/** @var Node|NodeExpressionBinary $condition */
		$condition = $node->condition;

		$alloc = $this->generateExpression($condition, true, true);

		if ($node->else === null) {
			$this->emit("if (".$this->unwrap($alloc).") {");
			$this->generateThenBlock($node->then);
			$this->emit("}");
		} else {
			$this->emit("if (".$this->unwrap($alloc).") {");
			$this->generateThenBlock($node->then);
			$this->emit("} else {");
				$this->generateThenBlock($node->else, $labelEndIf);
			$this->emit("}");
		}
	}

	/**
	 * @param NodeScope|Node|object $node
	 * @param string|null $labelEndIf
	 */
	protected function generateThenBlock(object $node, ?string $labelEndIf = null): void {
		if (
			$node->node === NodeType::STATEMENT_ELSE_IF ||
			$node->node === NodeType::STATEMENT_IF
		) {
			$this->scopeNesting++;
				$this->generateStmtIf($node, $labelEndIf);
			$this->scopeNesting--;
			return;
		}

		$this->beginBlock();
		if ($node->node === NodeType::STATEMENT_SCOPE) {
			foreach ($node->statements as $statement) {
				$this->generateStmt($statement);
			}
		} else {
			$this->generateStmt($node);
		}
		$this->endBlock();
	}

	/**
	 * @param NodeStmtWhileLoop|object $node
	 */
	protected function generateStmtWhileLoop(object $node): void {
		/** @var Node|NodeExpressionBinary $condition */
		$condition = $node->condition;

		$alloc = $this->generateExpression($condition, true, true);

		$this->emit( "while (".$this->unwrap($alloc).") {" );
		$this->beginBlock();

			foreach ($node->statements as $statement) {
				$this->generateStmt($statement);
			}

		$this->endBlock();
		$this->emit("}");
	}

	/**
	 * @param NodeStmtForLoop|object $node
	 */
	protected function generateStmtForLoop(object $node): void {
		$identifier = $node->identifier;

		$assignment = $this->generateExpressionAssignment((object) [
			"node" => NodeType::EXPRESSION_ASSIGNMENT,
			"operator" => "=",
			"left" => $identifier,
			"right" => $node->from,
			"line" => $node->from->line, "col" => $node->from->col,
		]);
		if ($identifier->node === NodeType::DECLARATION_VARIABLE) {
			$identifier = $identifier->identifier;
		}

		if (
			$node->from->node === NodeType::LITERAL_INT &&
			$node->to->node === NodeType::LITERAL_INT &&
			$node->from->value > $node->to->value
		) {
			$step = -1;
			$conditionOperator = $node->exclusive ? ">" : ">=";
		} else {
			$step = 1;
			$conditionOperator = $node->exclusive ? "<" : "<=";
		}

		$allocRight = $this->generateExpression($node->to, true); // todo: ? disable variable modifications during this
		$this->emit( "for (".$this->unwrap($assignment)."; $".$identifier->name." ".$conditionOperator." ".$this->unwrap($allocRight)."; ".$this->generateIncrementVariable($identifier, $step).") {" );
		$this->beginBlock();

			foreach ($node->statements as $statement) {
				$this->generateStmt($statement);
			}

		$this->endBlock();
		$this->emit("}");
	}

	/**
	 * @param NodeDeclVariable|object $node
	 */
	protected function generateDeclVariable(object $node): void {
		$identifier = $node->identifier->name;

		/* if (isset($this->identifiers[$identifier])) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' is already declared");
		}
		$this->identifiers[$identifier] = (object) [
			'initScope' => 0,
			'mutable' => $node->mutable,
			'alloc' => $this->allocateStack(self::SIZE_32),
		];
		$this->identifiersStack[] = $identifier; */
		// $this->emitComment("declare {$identifier}");
	}

	protected function beginBlock(): void {
		$this->scopeNesting++;
		// return $this->stackPos;
	}

	/**
	 * remove variables declared during block body, restore stack pointer
	 */
	protected function endBlock(): void {
		$this->scopeNesting--;

		/* $this->scopeNesting--;
		foreach ($this->identifiers as $identifier => $data) {
			if ($data === null) {
				continue;
			}

			if ($data->initScope > $this->scopeNesting) {
				$data->initScope = 0;
			}
		}

		$removeCount = $this->stackPos - $prevStackPos;
		if ($removeCount === 0) {
			return;
		}
		$removed = array_splice($this->identifiersStack, $prevStackPos, $removeCount);
		foreach ($removed as $identifier) {
			$this->identifiers[$identifier] = null; // or unset($this->identifiers[$identifier])
		}
		$this->stackPos = $prevStackPos; */
	}

	/**
	 * @param NodeStmtExpression|object $node
	 */
	protected function generateStmtExpression(object $node): void {
		$expr = $this->generateExpression($node->value, false);
		$this->emit($this->unwrap($expr) . ";");
	}

	/**
	 * @param Node|object $node
	 */
	protected function generateStmt(object $node): void {
		if ($node->node === NodeType::STATEMENT_EXPRESSION) {
			$this->generateStmtExpression($node);
		} else if ($node->node === NodeType::EXPRESSION_ASSIGNMENT) {
			$this->emit($this->unwrap($this->generateExpressionAssignment($node)) . ";");
		} else if ($node->node === NodeType::EXPRESSION_UPDATE) {
			$this->emit($this->unwrap($this->generateExpressionUpdate($node, false)) . ";");

		} else if ($node->node === NodeType::STATEMENT_IO_PRINT) { // todo: temporary io.print
			$this->generateStmtIOPrint($node);

		} else if ($node->node === NodeType::STATEMENT_RETURN) {
			$this->generateStmtReturn($node);
		} else if (
			$node->node === NodeType::STATEMENT_IF ||
			$node->node === NodeType::STATEMENT_ELSE_IF
		) {
			$this->generateStmtIf($node);
		} else if ($node->node === NodeType::STATEMENT_WHILE_LOOP) {
			$this->generateStmtWhileLoop($node);
		} else if ($node->node === NodeType::STATEMENT_FOR_LOOP) {
			$this->generateStmtForLoop($node);
		} else if ($node->node === NodeType::DECLARATION_VARIABLE) {
			// $this->generateDeclVariable($node); // skip
		} else if ($node->node === NodeType::DECLARATION_TYPE) {
			// do nothing
		} else if ($node->node === NodeType::STATEMENT_NOOP) {
			// do nothing
		} else {
			$nodeType = Lang::getNodeTypeName($node);
			throw new CompilerRuntimeException("Wrong node type: {$nodeType}");
		}
	}

	/** @var string */
	protected $fileName;

	/**
	 * @param NodeProgram|object $node
	 * @param string $fileName
	 */
	public function generate(object $node, string $fileName): void {
		$this->fileName = $fileName;
		$this->init();

		ob_start();
		foreach ($node->statements as $statement) {
			$this->generateStmt($statement);
		}
		$buffer = ob_get_clean();

		echo "<?php\n";
		if (isset($this->usedBuiltIns["io.print()"])) {
			echo "class io {\n";
			echo "    public static function print(\$s) { echo \$s.PHP_EOL; }\n";
			echo "}\n";
		}

		echo "\n";
		echo $buffer;
	}

}
