<?php

class GeneratorFasm_win_x86_64 implements GeneratorInterface {

	/** @var int */
	protected $stackPos;

	/** @var int */
	protected $scopeNesting;

	/** @var IdentifierData[] */
	protected $identifiers;

	/** @var array */
	protected $identifiersStack;

	/** @var array */
	protected $allocated;

	/** @var int */
	protected $labelIndex;

	/** @var Instruction[] */
	protected $instructions;

	/** @var int */
	protected $prevSwappableIndex;

	protected const EMIT_INSTRUCTION = 1;
	protected const EMIT_INSTRUCTION_JUMP = 2;
	protected const EMIT_LABEL = 3;
	protected const EMIT_COMMENT = 4;

	protected const ROOT_SCOPE_NESTING = 1;

	// registers
	protected const REGISTER_NONE = 12;
	protected const REGISTER_A = 0;
	protected const REGISTER_C = 1;
	protected const REGISTER_D = 2;
	protected const REGISTER_B = 3;
	protected const REGISTER_8 = 4;
	protected const REGISTER_9 = 5;
	protected const REGISTER_10 = 6;
	protected const REGISTER_11 = 7;
	protected const REGISTER_12 = 8;
	protected const REGISTER_13 = 9;
	protected const REGISTER_14 = 10;
	protected const REGISTER_15 = 11;

	// instruction argument types
	protected const ARG_NONE = 0;
	protected const ARG_IMMEDIATE = 1;
	protected const ARG_REGISTER = 2;
	protected const ARG_ADDRESS = 3;
	protected const ARG_STACK_64 = 6;
	protected const ARG_STACK_32 = 6 + self::SIZE_32;
	protected const ARG_STACK_16 = 6 + self::SIZE_16;
	protected const ARG_STACK_8 = 6 + self::SIZE_8;

	// allocation types
	protected const ALLOCATION_IMMEDIATE = 1;
	protected const ALLOCATION_REGISTER = 2;
	protected const ALLOCATION_RESERVED = 4;
	protected const ALLOCATION_CMP_FLAG = 5;
	protected const ALLOCATION_STACK = 6;

	protected const CMP_EQ = 1;
	protected const CMP_NOT_EQ = 2;
	protected const CMP_L_EQ = 3;
	protected const CMP_G_EQ = 4;
	protected const CMP_L = 5;
	protected const CMP_G = 6;

	protected const SIZE_64 = 0;
	protected const SIZE_32 = 12;
	protected const SIZE_16 = 24;
	protected const SIZE_8 = 36;

	protected static $registers = [
		// (ABI) function call might overwrite those [
		self::REGISTER_A => 'rax',
		self::REGISTER_C => 'rcx',
		self::REGISTER_D => 'rdx',
		self::REGISTER_B => 'rbx', // except B
		self::REGISTER_8 => 'r8',
		self::REGISTER_9 => 'r9',
		self::REGISTER_10 => 'r10',
		self::REGISTER_11 => 'r11',
		// ]

		self::REGISTER_12 => 'r12',
		self::REGISTER_13 => 'r13',
		self::REGISTER_14 => 'r14',
		self::REGISTER_15 => 'r15',

		// win ABI:
		// rax arg0
		// rcx arg1
		// r8  arg2
		// r9  arg3
		// rax ret

		// linux ABI:
		// rdi arg0
		// rsi arg1
		// rdx arg2
		// rcx arg3
		// r8  arg4
		// r9  arg5
		// rax ret

		// rsi, esi, si, sil
		// rdi, edi, di, dil


		// reserved
		// rbp, ebp, bp, bpl
		// rsp, esp, sp, spl

		self::REGISTER_A + self::SIZE_32 => 'eax',
		self::REGISTER_C + self::SIZE_32 => 'ecx',
		self::REGISTER_D + self::SIZE_32 => 'edx',
		self::REGISTER_B + self::SIZE_32 => 'ebx',
		self::REGISTER_8 + self::SIZE_32 => 'r8d',
		self::REGISTER_9 + self::SIZE_32 => 'r9d',
		self::REGISTER_10 + self::SIZE_32 => 'r10d',
		self::REGISTER_11 + self::SIZE_32 => 'r11d',

		self::REGISTER_12 + self::SIZE_32 => 'r12d',
		self::REGISTER_13 + self::SIZE_32 => 'r13d',
		self::REGISTER_14 + self::SIZE_32 => 'r14d',
		self::REGISTER_15 + self::SIZE_32 => 'r15d',

		self::REGISTER_A + self::SIZE_16 => 'ax',
		self::REGISTER_C + self::SIZE_16 => 'cx',
		self::REGISTER_D + self::SIZE_16 => 'dx',
		self::REGISTER_B + self::SIZE_16 => 'bx',
		self::REGISTER_8 + self::SIZE_16 => 'r8w',
		self::REGISTER_9 + self::SIZE_16 => 'r9w',
		self::REGISTER_10 + self::SIZE_16 => 'r10w',
		self::REGISTER_11 + self::SIZE_16 => 'r11w',

		self::REGISTER_12 + self::SIZE_16 => 'r12w',
		self::REGISTER_13 + self::SIZE_16 => 'r13w',
		self::REGISTER_14 + self::SIZE_16 => 'r14w',
		self::REGISTER_15 + self::SIZE_16 => 'r15w',

		self::REGISTER_A + self::SIZE_8 => 'al',
		self::REGISTER_C + self::SIZE_8 => 'cl',
		self::REGISTER_D + self::SIZE_8 => 'dl',
		self::REGISTER_B + self::SIZE_8 => 'bl',
		self::REGISTER_8 + self::SIZE_8 => 'r8b',
		self::REGISTER_9 + self::SIZE_8 => 'r9b',
		self::REGISTER_10 + self::SIZE_8 => 'r10b',
		self::REGISTER_11 + self::SIZE_8 => 'r11b',

		self::REGISTER_12 + self::SIZE_8 => 'r12b',
		self::REGISTER_13 + self::SIZE_8 => 'r13b',
		self::REGISTER_14 + self::SIZE_8 => 'r14b',
		self::REGISTER_15 + self::SIZE_8 => 'r15b',


		// self::REGISTER_A + self::SIZE_8_HIGH => 'ah',
		// self::REGISTER_C + self::SIZE_8_HIGH => 'ch',
		// self::REGISTER_D + self::SIZE_8_HIGH => 'dh',
		// self::REGISTER_B + self::SIZE_8_HIGH => 'bh',
	];

	protected function init(): void {
		$this->stackPos = 0;
		$this->scopeNesting = self::ROOT_SCOPE_NESTING;
		$this->identifiers = [];
		$this->identifiersStack = [];
		$this->allocated = [];
		for ($i = self::REGISTER_A; $i <= self::REGISTER_15; $i++) {
			$this->allocated[$i] = null;
		}
		$this->instructions = [];
		$this->prevSwappableIndex = 0;

		$this->labelIndex = 0;
	}

	/**
	 * @param Instruction|object $instr
	 * @return bool
	 */
	protected function tryFuseMov(object $instr): bool {
		if (
			$this->prevSwappableIndex !== 0 &&
			$instr->nArgs === 2 &&
			$instr->arg1Type === self::ARG_REGISTER &&
			$instr->arg2Type === self::ARG_REGISTER
		) {
			$prevSwappable = $this->instructions[$this->prevSwappableIndex];
			if (
				$instr->arg1 === $prevSwappable->arg2 &&
				$instr->arg2 === $prevSwappable->arg1
			) {
				// add reg1, reg2
				// mov reg2, reg1
				//   ->
				// add reg2, reg1
				$this->instructions[$this->prevSwappableIndex] = (object) [
					'type' => self::EMIT_INSTRUCTION,
					'instruction' => $prevSwappable->instruction,
					'arg1' => $instr->arg1, 'arg1Type' => self::ARG_REGISTER,
					'arg2' => $instr->arg2, 'arg2Type' => self::ARG_REGISTER,
					'arg3' => null, 'arg3Type' => self::ARG_NONE,
					'text' => '',
					'nArgs' => 2,
				];
			}
			return true;
		}
		return false;
	}

	/**
	 * @param Instruction|object $instr
	 */
	protected function checkSwappable(object $instr): void {
		if ($instr->type === self::EMIT_COMMENT) {
			return;
		}

		if (
			$instr->type === self::EMIT_INSTRUCTION &&
			$instr->nArgs === 2 &&
			$instr->arg1Type === self::ARG_REGISTER &&
			$instr->arg2Type === self::ARG_REGISTER && (
				$instr->instruction === 'add' ||
				$instr->instruction === 'sub' ||
				$instr->instruction === 'and' ||
				$instr->instruction === 'or' ||
				$instr->instruction === 'xor' ||
				$instr->instruction === 'imul'
			)
		) {
			$this->prevSwappableIndex = count($this->instructions);
			return;
		}
		$this->prevSwappableIndex = 0;
	}

	/**
	 * @param Allocation|object $alloc
	 * @return array
	 */
	protected function emitInstructionArg(object $alloc): array {
		if ($alloc->type === self::ALLOCATION_REGISTER) {
			return [$alloc->value + $alloc->size, self::ARG_REGISTER];
		}
		if ($alloc->type === self::ALLOCATION_IMMEDIATE) {
			return [$alloc->value, self::ARG_IMMEDIATE];
		}
		if ($alloc->type === self::ALLOCATION_STACK) {
			return [$alloc->value * 4, self::ARG_STACK_64 + $alloc->size];
		}
		throw new CompilerRuntimeException("Invalid emit argument alloc: {$alloc->type}");
	}

	/**
	 * @param string $instruction
	 * @param Allocation|object|null $arg1
	 * @param Allocation|object|null $arg2
	 * @param Allocation|object|null $arg3
	 */
	protected function emit(string $instruction, $arg1 = null, $arg2 = null, $arg3 = null): void {
		$arg1Value = 0; $arg1Type = self::ARG_NONE;
		$arg2Value = 0; $arg2Type = self::ARG_NONE;
		$arg3Value = 0; $arg3Type = self::ARG_NONE;
		$nArgs = 0;
		if ($arg1 !== null) {
			$nArgs = 1;
			[$arg1Value, $arg1Type] = $this->emitInstructionArg($arg1);
			if ($arg2 !== null) {
				$nArgs = 2;
				[$arg2Value, $arg2Type] = $this->emitInstructionArg($arg2);
				if ($arg3 !== null) {
					$nArgs = 3;
					[$arg3Value, $arg3Type] = $this->emitInstructionArg($arg3);
				}
			}
		}
		$instr = (object) [
			'type' => self::EMIT_INSTRUCTION,
			'instruction' => $instruction,
			'arg1' => $arg1Value, 'arg1Type' => $arg1Type,
			'arg2' => $arg2Value, 'arg2Type' => $arg2Type,
			'arg3' => $arg3Value, 'arg3Type' => $arg3Type,
			'text' => '',
			'nArgs' => $nArgs,
		];
		if (
			$instruction === 'mov' &&
			$this->tryFuseMov($instr)
		) {
			return;
		}
		$this->checkSwappable($instr);
		$this->instructions[] = $instr;
	}

	protected function emitJump(string $instruction, string $label): void {
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION_JUMP,
			'instruction' => $instruction,
			'arg1' => 0, 'arg1Type' => self::ARG_NONE,
			'arg2' => 0, 'arg2Type' => self::ARG_NONE,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => $label,
			'nArgs' => 0,
		];
	}

	/**
	 * @param Allocation|object $arg1
	 * @param string $arg2
	 */
	protected function emitLea(object $arg1, string $arg2): void {
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION,
			'instruction' => 'lea',
			'arg1' => $arg1->value + $arg1->size, 'arg1Type' => self::ARG_REGISTER,
			'arg2' => 0, 'arg2Type' => self::ARG_NONE,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => $arg2,
			'nArgs' => 0,
		];
	}

	protected function emitCall(string $name): void {
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION,
			'instruction' => 'call',
			'arg1' => 0, 'arg1Type' => self::ARG_NONE,
			'arg2' => 0, 'arg2Type' => self::ARG_NONE,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => $name,
			'nArgs' => 0,
		];
	}

	protected function emitLabel(string $label): void {
		$this->instructions[] = (object) [
			'type' => self::EMIT_LABEL,
			'instruction' => '',
			'arg1' => 0, 'arg1Type' => self::ARG_NONE,
			'arg2' => 0, 'arg2Type' => self::ARG_NONE,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => $label,
			'nArgs' => 0,
		];
	}

	protected function emitComment(string $comment): void {
		$this->instructions[] = (object) [
			'type' => self::EMIT_COMMENT,
			'instruction' => '',
			'arg1' => 0, 'arg1Type' => self::ARG_NONE,
			'arg2' => 0, 'arg2Type' => self::ARG_NONE,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => $comment,
			'nArgs' => 0,
		];
	}

	protected function instructionArg(int $arg, int $argType): string {
		if ($argType === self::ARG_REGISTER) {
			return self::$registers[$arg];
		}
		if ($argType === self::ARG_IMMEDIATE) {
			return (string) $arg;
		}
		if ($argType === self::ARG_STACK_64) {
			return "QWORD [rbp-{$arg}]";
		}
		if ($argType === self::ARG_STACK_32) {
			return "DWORD [rbp-{$arg}]";
		}
		if ($argType === self::ARG_STACK_16) {
			return "WORD [rbp-{$arg}]";
		}
		if ($argType === self::ARG_STACK_8) {
			return "BYTE [rbp-{$arg}]";
		}
		throw new CompilerRuntimeException("Invalid instruction argument type: {$argType}");
	}

	protected function emitInstructions(): void {
		foreach ($this->instructions as $instr) {
			if ($instr->type === self::EMIT_INSTRUCTION) {
				if ($instr->instruction === 'lea') {
					$arg1 = self::$registers[$instr->arg1];
					echo "    lea     {$arg1}, [{$instr->text}]\n";
					continue;
				}
				if ($instr->instruction === 'call') {
					echo "    call    [{$instr->text}]\n";
					continue;
				}
				if ($instr->nArgs === 0) {
					echo "    {$instr->instruction}\n";
					continue;
				}
				$arg1 = $this->instructionArg($instr->arg1, $instr->arg1Type);
				echo '    ' . str_pad($instr->instruction, 7) . " {$arg1}";
				if ($instr->nArgs >= 2) {
					$arg2 = $this->instructionArg($instr->arg2, $instr->arg2Type);
					echo ", {$arg2}";
					if ($instr->nArgs >= 3) {
						$arg3 = $this->instructionArg($instr->arg3, $instr->arg3Type);
						echo ", {$arg3}";
					}
				}
				echo "\n";
			} else if ($instr->type === self::EMIT_INSTRUCTION_JUMP) {
				echo '    ' . str_pad($instr->instruction, 7) . " {$instr->text}\n";
			} else if ($instr->type === self::EMIT_LABEL) {
				echo "{$instr->text}:\n";
			} else if ($instr->type === self::EMIT_COMMENT) {
				echo "                                ; {$instr->text}\n";
			}
		}
	}

	/* protected function push(string $fromReg): object {
		$this->stackPos++;
		echo "    push {$fromReg}\n";
	} */

	/* protected function pop(string $intoReg): object {
		$this->stackPos--;
		echo "    pop {$intoReg}\n";
	} */

	protected function newLabel(): string {
		$this->labelIndex++;
		return ".L{$this->labelIndex}";
	}

	protected function getFreeRegister(): int {
		// for ($index = $this->lastRegIndex; $index < $this->lastRegIndex + self::REGISTER_9; $index++) {
		for ($regIndex = self::REGISTER_A; $regIndex <= self::REGISTER_15; $regIndex++) {
			// $regIndex = 1 + $index % self::REGISTER_15;
			if ($this->allocated[$regIndex] === null) {
				return $regIndex;
			}
		}
		return self::REGISTER_NONE;
	}

	protected function deallocateRegisterByIndex(int $regIndex): void {
		$this->allocated[$regIndex] = null;
	}
	
	/**
	 * @param Allocation|object $alloc
	 */
	protected function deallocateRegister(object $alloc): void {
		$this->allocated[$alloc->value] = null;
	}

	/**
	 * @param Allocation|object $alloc
	 */
	protected function deallocateIfRegister(object $alloc): void {
		if ($alloc->type === self::ALLOCATION_REGISTER) {
			$this->allocated[$alloc->value] = null;
		}
	}

	/**
	 * @param int $regSize
	 * @return Allocation
	 */
	protected function allocateFreeRegister(int $regSize): object {
		$regIndex = $this->getFreeRegister();
		if ($regIndex === self::REGISTER_NONE) {
			throw new CompilerRuntimeException("Not enough registers");
		}
		$alloc = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => $regIndex,
			'size' => $regSize,
		];
		$this->allocated[$regIndex] = $alloc;
		return $alloc;
	}

	/**
	 * @param int $regIndex
	 * @param int $regSize
	 * @return Allocation
	 */
	protected function allocateRegister(int $regIndex, int $regSize): object {
		$alloc = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => $regIndex,
			'size' => $regSize,
		];
		$this->allocated[$regIndex] = $alloc;
		return $alloc;
	}

	/**
	 * @param Allocation|object $alloc
	 * @param bool $zeroAsXor
	 * @return Allocation
	 */
	protected function allocateFreeRegisterFrom(object $alloc, bool $zeroAsXor = false): object {
		$allocRes = $this->allocateFreeRegister($alloc->size);
		if (
			$zeroAsXor &&
			$alloc->type === self::ALLOCATION_IMMEDIATE &&
			$alloc->value === 0
		) {
			// mov reg, 0
			//   ->
			// xor reg, reg
			$this->emit('xor', $allocRes, $allocRes);
		} else {
			$this->emit('mov', $allocRes, $alloc);
		}
		return $allocRes;
	}

	/**
	 * @param Allocation|object $alloc
	 * @return Allocation
	 */
	protected function allocateFreeRegisterWithDeallocationFromRegister(object $alloc): object {
		$allocRes = $this->allocateFreeRegister($alloc->size);
		$this->deallocateRegister($alloc);
		$this->emit('mov', $allocRes, $alloc);
		return $allocRes;
	}

	/* *
	 * @param int $regSize
	 * @return Allocation
	 */
	/* protected function allocateFreeRegisterZero(int $regSize): object {
		$alloc = $this->allocateFreeRegister($regSize);
		$reg = $this->getAllocationRegister($alloc);
		echo "    xor     {$reg}, {$reg}\n";
		return $alloc;
	} */

	protected function generateClearXor(int $regIndex, int $regSize): void {
		$alloc = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => $regIndex,
			'size' => $regSize,
		];
		$this->emit('xor', $alloc, $alloc);
	}

	/**
	 * @param int $size
	 * @return Allocation
	 */
	protected function allocateStack(int $size): object {
		$this->stackPos++; // todo: size
		return (object) [
			'type' => self::ALLOCATION_STACK,
			'value' => $this->stackPos,
			'size' => $size,
		];
	}

	/**
	 * @param Allocation|object $alloc
	 * @return Allocation
	 */
	protected function pushRegister(object $alloc): object {
		$allocRight = clone $alloc;
		$allocRegIndex = $this->getFreeRegister();
		if ($allocRegIndex === self::REGISTER_NONE) {
			$allocRes = $this->allocateStack($alloc->size);
		} else {
			// move allocation
			// $alloc = $this->allocated[$regIndex];
			$allocRes = $alloc;
			$allocRes->value = $allocRegIndex;
			$this->allocated[$allocRegIndex] = $allocRes;

			// deallocation is skipped because used in division
			// and both A and D registers need to stay allocated
			// $this->deallocateRegister($regIndex);
		}
		$this->emit('mov', $allocRes, $allocRight);
		return $allocRes;
	}

	/**
	 * @param Allocation|object $alloc
	 * @param int $regIndex
	 */
	protected function popRegister(object $alloc, int $regIndex): void {
		$this->stackPos--;
		$allocLeft = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => $regIndex,
			'size' => $alloc->size,
		];
		$this->emit('mov', $allocLeft, $alloc);
	}

	/**
	 * @param int $value
	 * @return Allocation
	 */
	protected function createImmediate(int $value): object {
		return (object) [
			'type' => self::ALLOCATION_IMMEDIATE,
			'value' => $value,
			'size' => self::SIZE_32,
		];
	}

	/**
	 * @param NodeLiteralInt|object $node
	 * @return Allocation
	 */
	protected function generateLiteralInt(object $node): object {
		return (object) [
			'type' => self::ALLOCATION_IMMEDIATE,
			'value' => $node->value,
			'size' => self::SIZE_32, // todo: extend to 64 for big literals
		];
	}

	/**
	 * @param NodeLiteralBool|object $node
	 * @return Allocation
	 */
	protected function generateLiteralBool(object $node): object {
		return (object) [
			'type' => self::ALLOCATION_IMMEDIATE,
			'value' => $node->value,
			'size' => self::SIZE_32, // todo: maybe use smaller size
		];
	}

	/**
	 * @param NodeIdentifier|object $node
	 * @return Allocation
	 */
	protected function generateExpressionIdentifier(object $node): object {
		$identifier = $node->name;
		if (!isset($this->identifiers[$identifier])) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' is not declared");
		}
		$data = $this->identifiers[$identifier];
		if ($data->initScope === 0) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Usage of uninitialized variable '{$identifier}'");
		}
		return $data->alloc;
	}

	/**
	 * @param Allocation|object $alloc
	 * @param int $regIndex
	 * @return bool
	 */
	protected function isRegister(object $alloc, int $regIndex): bool {
		return (
			$alloc->type === self::ALLOCATION_REGISTER &&
			$alloc->value === $regIndex
		);
	}

	/**
	 * @param Allocation|object $allocLeft
	 * @param Allocation|object $allocRight
	 * @return object
	 */
	protected function generateMultiply(object $allocLeft, object $allocRight): object {
		if ($allocLeft->type === self::ALLOCATION_STACK) {
			if ($allocRight->type === self::ALLOCATION_STACK) {
				$allocLeft = $this->allocateFreeRegisterFrom($allocLeft);
				// prev $allocLeft doesn't need deallocation because it's not a register
			}
		} else if ($allocLeft->type === self::ALLOCATION_IMMEDIATE) {
			if ($allocRight->type === self::ALLOCATION_IMMEDIATE) {
				$allocLeft = $this->allocateFreeRegisterFrom($allocLeft, true);
				// prev $allocLeft doesn't need deallocation because it's not a register
			} else { // and right is mot immediate
				// swap left and right operands
				$tmp = $allocLeft;
				$allocLeft = $allocRight;
				$allocRight = $tmp;
			}
		}

		$this->deallocateIfRegister($allocRight);

		if ($allocLeft->type === self::ALLOCATION_REGISTER) {
			$this->emit('imul', $allocLeft, $allocRight);
			return $allocLeft;
		}

		/* if (
			$allocLeft->type === self::ALLOCATION_STACK || // and right is not stack
			$allocLeft->type === self::ALLOCATION_IMMEDIATE // and right is not immediate
		) { */
		// no need to deallocate $allocLeft because it's not a register
		$allocRes = $this->allocateFreeRegister($allocLeft->size); // todo: size, prob max($allocLeft->size, $allocRight->size)
		$this->emit('imul', $allocRes, $allocLeft, $allocRight);
		return $allocRes;
		// }
	}

	/**
	 * @param Allocation|object $allocLeft
	 * @param Allocation|object $allocRight
	 * @param bool $isOperatorReminder
	 * @return object
	 */
	protected function generateDivide(object $allocLeft, object $allocRight, bool $isOperatorReminder): object {
		$tempA = null;
		$tempD = null;
		/** @var Allocation|null $allocatedA */
		$allocatedA = $this->allocated[self::REGISTER_A];
		/** @var Allocation|null $allocatedD */
		$allocatedD = $this->allocated[self::REGISTER_D];
		if ($allocatedA === null) {
			$this->allocated[self::REGISTER_A] = (object) [
				'type' => self::ALLOCATION_RESERVED,
				'value' => self::REGISTER_A,
				'size' => 0, // no size
			];
		}
		if ($allocatedD === null) {
			$this->allocated[self::REGISTER_D] = (object) [
				'type' => self::ALLOCATION_RESERVED,
				'value' => self::REGISTER_D,
				'size' => 0, // no size
			];
		}
		// both A and D allocated or reserved up until now

		if (
			$allocatedA !== null &&
			!$this->isRegister($allocLeft, self::REGISTER_A) &&
			!$this->isRegister($allocRight, self::REGISTER_A)
		) {
			// backup register to another free register or stack (without deallocation)
			$tempA = $this->pushRegister($allocatedA);
		}
		if (
			$allocatedD !== null &&
			!$this->isRegister($allocLeft, self::REGISTER_D) &&
			!$this->isRegister($allocRight, self::REGISTER_D)
		) {
			// backup register to another free register or stack (without deallocation)
			$tempD = $this->pushRegister($allocatedD);
		}

		if (
			$allocRight->type === self::ALLOCATION_REGISTER && (
				$allocRight->value === self::REGISTER_A ||
				$allocRight->value === self::REGISTER_D
			)
		) {
			$allocRight = $this->allocateFreeRegisterFrom($allocRight);
			// prev $allocRight doesn't need deallocation because both A and D guaranteed to be allocated
		}

		if (!$this->isRegister($allocLeft, self::REGISTER_A)) {
			$this->deallocateIfRegister($allocLeft);
			$regA = (object) [
				'type' => self::ALLOCATION_REGISTER,
				'value' => self::REGISTER_A,
				'size' => $allocLeft->size,
			];
			$this->emit('mov', $regA, $allocLeft);
		}

		if ($allocRight->type === self::ALLOCATION_IMMEDIATE) {
			$allocRight = $this->allocateFreeRegisterFrom($allocRight, true);
			// prev $allocRight doesn't need deallocation because it's not a register
		}
		$this->deallocateIfRegister($allocRight);

		if ($allocLeft->type === self::ALLOCATION_IMMEDIATE && $allocLeft->value >= 0) {
			// dividend is immediate and non-negative. skip sign extending into edx
			$this->generateClearXor(self::REGISTER_D, $allocRight->size);
		} else {
			// cdq extends signed eax into edx:eax
			$this->emit('cdq'); // cqo for 64-bit
		}
		$this->emit('idiv', $allocRight);

		// unlock reserved registers or deallocate registers used for operands
		$this->deallocateRegisterByIndex(self::REGISTER_A);
		$this->deallocateRegisterByIndex(self::REGISTER_D);

		$allocRes = $isOperatorReminder
			? $this->allocateRegister(self::REGISTER_D, self::SIZE_32)
			: $this->allocateRegister(self::REGISTER_A, self::SIZE_32); // todo: size

		if (
			$tempD !== null &&
			$tempD->type === self::ALLOCATION_STACK
		) {
			if ($allocRes->value === self::REGISTER_D) {
				$allocRes = $this->allocateFreeRegisterWithDeallocationFromRegister($allocRes);
			}
			$this->popRegister($tempD, self::REGISTER_D);
		}

		if (
			$tempA !== null &&
			$tempA->type === self::ALLOCATION_STACK
		) {
			if ($allocRes->value === self::REGISTER_A) {
				$allocRes = $this->allocateFreeRegisterWithDeallocationFromRegister($allocRes);
			}
			$this->popRegister($tempA, self::REGISTER_A);
		}

		return $allocRes;
	}

	/**
	 * @param Allocation|object $allocLeft
	 * @param Allocation|object $allocRight
	 * @param string $operator
	 * @param bool $allocAsCmpFlag
	 * @return object
	 */
	protected function generateComparison(object $allocLeft, object $allocRight, string $operator, bool $allocAsCmpFlag): object {
		if ($allocAsCmpFlag) {
			if (
				$allocLeft->type === self::ALLOCATION_IMMEDIATE || (
					$allocLeft->type === self::ALLOCATION_STACK &&
					$allocRight->type !== self::ALLOCATION_IMMEDIATE
				)
			) {
				// cmp stack, stack
				// cmp stack, reg
				// cmp immediate, *
				//   ->
				// cmp reg, *
				$allocLeft = $this->allocateFreeRegisterFrom($allocLeft, true);
				// prev $allocLeft doesn't need deallocation because it's not a register
			}

			$this->deallocateIfRegister($allocRight);
			$this->emit('cmp', $allocLeft, $allocRight);
			return (object) [
				'type' => self::ALLOCATION_CMP_FLAG,
				'value' => 0,
				'size' => 0, // no size
			];
		}

		if ($allocLeft->type !== self::ALLOCATION_REGISTER) {
			$allocLeft = $this->allocateFreeRegisterFrom($allocLeft, true);
			// prev $allocLeft doesn't need deallocation because it's not a register
		}

		$this->deallocateIfRegister($allocRight);

		$allocLeftU8 = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => $allocLeft->value,
			'size' => self::SIZE_8,
		];
		$this->emit('cmp', $allocLeft, $allocRight);
		if ($operator === '==') {
			$this->emit('sete', $allocLeftU8);
		} else if ($operator === '!=') {
			$this->emit('setne', $allocLeftU8);
		} else if ($operator === '<=') {
			// "setbe" for unsigned
			$this->emit('setle', $allocLeftU8);
		} else if ($operator === '>=') {
			// "setae" for unsigned
			$this->emit('setge', $allocLeftU8);
		} else if ($operator === '<') {
			$this->emit('setl', $allocLeftU8);
		} else if ($operator === '>') {
			$this->emit('setg', $allocLeftU8);
		}
		$this->emit('and', $allocLeftU8, $this->createImmediate(1));
		$this->emit('movzx', $allocLeft, $allocLeftU8);
		return $allocLeft; // todo: consider size
	}

	/**
	 * @param NodeExpressionBinary|object $node
	 * @param bool $allocAsCmpFlag
	 * @return Allocation
	 */
	protected function generateExpressionBinary(object $node, bool $allocAsCmpFlag = false): object {
		$allocLeft = $this->generateExpression($node->left, true);
		$allocRight = $this->generateExpression($node->right, true);

		if ($node->operator === '*') {
			return $this->generateMultiply($allocLeft, $allocRight);
		}
		if ($node->operator === '/') {
			return $this->generateDivide($allocLeft, $allocRight, false);
		}
		if ($node->operator === '%') {
			return $this->generateDivide($allocLeft, $allocRight, true);
		}


		if (
			$node->operator === '==' ||
			$node->operator === '!=' ||
			$node->operator === '<=' ||
			$node->operator === '>=' ||
			$node->operator === '<' ||
			$node->operator === '>'
		) {
			return $this->generateComparison($allocLeft, $allocRight, $node->operator, $allocAsCmpFlag);
		}

		if ($allocLeft->type !== self::ALLOCATION_REGISTER) {
			$allocLeft = $this->allocateFreeRegisterFrom($allocLeft, true);
			// prev $allocLeft doesn't need deallocation because it's not a register
		}

		// "add" and "sub" represented as "lea" instruction when target is 3rd operand
		// currently no point of having it enabled. just in case it will become benefitial in future
		/* if (
			$allocRight->type === self::ALLOCATION_IMMEDIATE && (
				$node->operator === '+' ||
				$node->operator === '-'
			)
		) {
			$allocRes = $this->allocateFreeRegister($allocRight->size);
			$regRes = $this->getAllocationRegister($allocRes);

			// $this->deallocateRegister($allocRight);
			$regLeft = $this->getAllocationRegister($allocLeft);
			$regRight = (string) $allocRight->value;

			if ($node->operator === '+') {
				echo "    lea {$regRes}, [{$regLeft}+{$regRight}]\n";
			} else if ($node->operator === '-') {
				echo "    lea {$regRes}, [{$regLeft}-{$regRight}]\n";
			}
			return $allocRes;
		} */


		if ($node->operator === '<<') {
			if ($allocRight->type !== self::ALLOCATION_REGISTER) {
				$allocRight = $this->allocateFreeRegisterFrom($allocRight, true);
				// prev $allocRight doesn't need deallocation because it's not a register
			}
			$this->deallocateRegister($allocRight);
			$allocRightU8 = (object) [
				'type' => self::ALLOCATION_REGISTER,
				'value' => $allocRight->value,
				'size' => self::SIZE_8,
			];
			$this->emit('shl', $allocLeft, $allocRightU8);
			return $allocLeft;
		}
		if ($node->operator === '>>') {
			if ($allocRight->type !== self::ALLOCATION_REGISTER) {
				$allocRight = $this->allocateFreeRegisterFrom($allocRight, true);
				// prev $allocRight doesn't need deallocation because it's not a register
			}
			$this->deallocateRegister($allocRight);
			$allocRightU8 = (object) [
				'type' => self::ALLOCATION_REGISTER,
				'value' => $allocRight->value,
				'size' => self::SIZE_8,
			];
			$this->emit('sar', $allocLeft, $allocRightU8);
			return $allocLeft;
		}

		$this->deallocateIfRegister($allocRight);


		// todo: maybe zero other bits except the lowest one (cast to bool)
		if ($node->operator === '&&') {
			$this->emit('and', $allocLeft, $allocRight);
			return $allocLeft;
		}
		if ($node->operator === '||') {
			$this->emit('or', $allocLeft, $allocRight);
			return $allocLeft;
		}

		if ($node->operator === '+') {
			$this->emit('add', $allocLeft, $allocRight);
			return $allocLeft;
		}
		if ($node->operator === '-') {
			$this->emit('sub', $allocLeft, $allocRight);
			return $allocLeft;
		}
		if ($node->operator === '&') {
			$this->emit('and', $allocLeft, $allocRight);
			return $allocLeft;
		}
		if ($node->operator === '|') {
			$this->emit('or', $allocLeft, $allocRight);
			return $allocLeft;
		}
		if ($node->operator === '^') {
			$this->emit('xor', $allocLeft, $allocRight);
			return $allocLeft;
		}

		throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Operator '{$node->operator}' is not implemented");
	}

	/**
	 * @param NodeExpressionUnary|object $node
	 * @param bool $isResultUsed
	 * @return Allocation
	 */
	protected function generateExpressionUnary(object $node, bool $isResultUsed): object {
		$alloc = $this->generateExpression($node->value, $isResultUsed);
		if ($node->operator === '+') {
			// do nothing
			return $alloc;
		}

		$isNegateOperator = ($node->operator === '-');

		if ($alloc->type !== self::ALLOCATION_REGISTER) {
			if (
				$isNegateOperator &&
				$alloc->type === self::ALLOCATION_IMMEDIATE &&
				$alloc->value === 0
			) {
				return $alloc;
			}
			// (op) stack/immediate
			//   ->
			// reg = stack/immediate
			// reg = (op) reg
			$alloc = $this->allocateFreeRegisterFrom($alloc);
			// prev $alloc doesn't need deallocation because it's not a register
		}

		if ($isNegateOperator) {
			$this->emit('neg', $alloc);
			return $alloc;
		}
		if ($node->operator === '~') {
			$this->emit('not', $alloc);
			return $alloc;
		}
		if ($node->operator === '!') {
			// todo: temporary bitwise not
			$this->emit('not', $alloc);
			return $alloc;
		}

		throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Operator '{$node->operator}' is not implemented");
	}

	/**
	 * @param NodeExpressionUpdate|object $node
	 * @param bool $isResultUsed
	 * @return Allocation
	 */
	protected function generateExpressionUpdate(object $node, bool $isResultUsed): object {
		if ($node->operator === '++') {
			$step = 1;
		} else if ($node->operator === '--') {
			$step = -1;
		} else {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Update operator '{$node->operator}' is not implemented");
		}

		if ($node->prefix || !$isResultUsed) {
			return $this->generateIncrementVariable($node->value, $step);
		}

		$alloc = $this->generateExpressionIdentifier($node->value);
		$allocRes = $this->allocateFreeRegister($alloc->size);
		$this->emit('mov', $allocRes, $alloc);
		$this->generateIncrementVariable($node->value, $step);
		return $allocRes;
	}

	/**
	 * @param NodeIdentifier|object $node
	 * @param int $step
	 * @return Allocation
	 */
	protected function generateIncrementVariable(object $node, int $step): object {
		$identifier = $node->name;

		if (!isset($this->identifiers[$identifier])) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' is not declared");
		}
		$data = $this->identifiers[$identifier];

		if (!$data->mutable) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Can't modify an immutable variable '{$identifier}'");
		}
		if ($data->initScope === 0) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Usage of uninitialized variable '{$identifier}'");
		}

		$alloc = $data->alloc;
		if ($step === 1) {
			$this->emitComment("{$identifier} += 1");
			$this->emit('inc', $alloc);
			return $alloc;
		}
		if ($step === -1) {
			$this->emitComment("{$identifier} -= 1");
			$this->emit('dec', $alloc);
			return $alloc;
		}

		throw new CompilerRuntimeException("Increment step '{$step}' should be '1' or '-1'");
	}

	/**
	 * @param NodeExpressionAssignment|object $node
	 * @return Allocation
	 */
	protected function generateExpressionAssignment(object $node): object {
		if ($node->left->node === NodeType::DECLARATION_VARIABLE) {
			$this->generateDeclVariable($node->left);
			$identifier = $node->left->identifier->name;
		} else {
			$identifier = $node->left->name;
		}

		$allocRight = $this->generateExpression($node->right, true);

		if (!isset($this->identifiers[$identifier])) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' is not declared");
		}
		$data = $this->identifiers[$identifier];

		if ($node->operator === '=') {
			$allocLeft = $data->alloc;

			if ($data->initScope === 0) {
				$data->initScope = $this->scopeNesting;
			}

			if ($allocRight->type === self::ALLOCATION_IMMEDIATE) {
				$this->emitComment("{$identifier} = {$allocRight->value}");
			} else {
				$this->emitComment("{$identifier} = (...)");
			}
			if ($allocRight->type === self::ALLOCATION_STACK) {
				// stack1 = stack2
				//   ->
				// reg = stack2
				// stack1 = reg
				$allocRight = $this->allocateFreeRegisterFrom($allocRight);
			}
			$this->deallocateIfRegister($allocRight);
			$this->emit('mov', $allocLeft, $allocRight);
			return $allocLeft;
		}

		if (!$data->mutable) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Can't modify an immutable variable '{$identifier}'");
		}
		if ($data->initScope === 0) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Usage of uninitialized variable '{$identifier}'");
		}

		$allocLeft = $data->alloc;

		if ($allocRight->type === self::ALLOCATION_IMMEDIATE && $allocRight->value === 1) {
			if ($node->operator === '+=') {
				$this->emitComment("{$identifier} += 1");
				$this->emit('inc', $allocLeft);
				return $allocLeft;
			}
			if ($node->operator === '-=') {
				$this->emitComment("{$identifier} -= 1");
				$this->emit('dec', $allocLeft);
				return $allocLeft;
			}
		}

		$this->emitComment("{$identifier} {$node->operator} (...)");
		if ($allocRight->type === self::ALLOCATION_STACK) {
			// stack1 (op) stack2
			//   ->
			// reg = stack2
			// stack1 (op) reg
			$allocRight = $this->allocateFreeRegisterFrom($allocRight);
		}
		$this->deallocateIfRegister($allocRight);

		if ($node->operator === '+=') {
			$this->emit('add', $allocLeft, $allocRight);
			return $allocLeft;
		}
		if ($node->operator === '-=') {
			$this->emit('sub', $allocLeft, $allocRight);
			return $allocLeft;
		}
		if ($node->operator === '*=') {
			$this->emit('imul', $allocLeft, $allocRight);
			return $allocLeft;
		}
		if ($node->operator === '&=') {
			$this->emit('and', $allocLeft, $allocRight);
			return $allocLeft;
		}
		if ($node->operator === '|=') {
			$this->emit('or', $allocLeft, $allocRight);
			return $allocLeft;
		}
		if ($node->operator === '^=') {
			$this->emit('xor', $allocLeft, $allocRight);
			return $allocLeft;
		}
		// todo: '/=' '%=' '<<=' '>>='

		throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Assignment operator '{$node->operator}' is not implemented");
		// throw new CompilerRuntimeException("{$this->fileName}:{$node->line}:{$node->col}: Unknown assignment operator '{$node->operator}'");
	}

	/**
	 * @param NodeStmtExpression|object $node
	 * @param bool $isResultUsed
	 * @param bool $allocAsCmpFlag
	 * @return Allocation
	 */
	protected function generateExpression(object $node, bool $isResultUsed, bool $allocAsCmpFlag = false): object {
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
		if (true) {
			throw new CompilerRuntimeException('Statement return is not implemented!');
		}
		$alloc = $this->generateExpression($node->value, true);
		$this->deallocateIfRegister($alloc);
		$this->emitComment("ret (...)");
		$regA = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => self::REGISTER_A,
			'size' => self::SIZE_64,
		];
		$this->emit('mov', $regA, $alloc); // todo: size
		$this->emit('ret');
	}

	// todo: temporary io.print
	/**
	 * @param NodeStmtReturn|object $node
	 */
	protected function generateStmtIOPrint(object $node): void {
		$alloc = $this->generateExpression($node->value, true);
		$this->deallocateIfRegister($alloc);
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
		$this->emitLea($regC, 'format_i32'); // todo: size
		// -- $this->pop('rdx');
		// -- echo "    pop     rdx\n";
		// -- echo "    mov     rdx, 5\n";
		$this->emitCall('printf');
	}

	/**
	 * @param Allocation|object $alloc
	 * @param string $label
	 * @param string|null $operator
	 */
	protected function generateCmpJumpToLabel(object $alloc, string $label, ?string $operator = null): void {
		if ($alloc->type === self::ALLOCATION_CMP_FLAG) {
			if ($operator === '==') {
				$this->emitJump('je', $label);
			} else if ($operator === '!=') {
				$this->emitJump('jne', $label);
			} else if ($operator === '<=') {
				$this->emitJump('jle', $label);
			} else if ($operator === '>=') {
				$this->emitJump('jge', $label);
			} else if ($operator === '<') {
				$this->emitJump('jl', $label);
			} else if ($operator === '>') {
				$this->emitJump('jg', $label);
			}
		} else {
			if ($alloc->type === self::ALLOCATION_STACK) {
				$this->emit('cmp', $alloc, $this->createImmediate(0));
			} else {
				$this->emit('test', $alloc, $alloc);
			}
			$this->emitJump('jne', $label); // jump if reg!=0
		}
	}

	/**
	 * @param Allocation|object $alloc
	 * @param string $label
	 * @param string|null $operator
	 */
	protected function generateCmpNegativeJumpToLabel(object $alloc, string $label, ?string $operator = null): void {
		if ($alloc->type === self::ALLOCATION_CMP_FLAG) {
			if ($operator === '==') {
				$this->emitJump('jne', $label); // !=
			} else if ($operator === '!=') {
				$this->emitJump('je', $label); // ==
			} else if ($operator === '<=') {
				$this->emitJump('jg', $label); // >
			} else if ($operator === '>=') {
				$this->emitJump('jl', $label); // <
			} else if ($operator === '<') {
				$this->emitJump('jge', $label); // >=
			} else if ($operator === '>') {
				$this->emitJump('jle', $label); // <=
			}
		} else {
			if ($alloc->type === self::ALLOCATION_STACK) {
				$this->emit('cmp', $alloc, $this->createImmediate(0));
			} else {
				$this->emit('test', $alloc, $alloc);
			}
			$this->emitJump('je', $label); // jump if reg==0
		}
	}

	/**
	 * @param NodeStmtIf|NodeStmtElseIf|object $node
	 * @param string|null $labelEndIf
	 */
	protected function generateStmtIf(object $node, ?string $labelEndIf = null): void {
		/** @var Node|NodeExpressionBinary $condition */
		$condition = $node->condition;

		$isRootIf = ($labelEndIf === null);
		if ($isRootIf) {
			$labelEndIf = $this->newLabel();
		}
		if ($node->node === NodeType::STATEMENT_ELSE_IF) {
			$this->emitComment("else if (...)");
		} else {
			$this->emitComment("if (...)");
		}

		$alloc = $this->generateExpression($condition, true, true);
		if ($alloc->type === self::ALLOCATION_IMMEDIATE) {
			// if (immediate)
			//   ->
			// reg = immediate
			// if (reg)
			// not using zeroAsXor because "if (0)" should not happen
			// after optimizing "always true/always false" conditions
			$alloc = $this->allocateFreeRegisterFrom($alloc);
		}
		$this->deallocateIfRegister($alloc);

		$operator = ($alloc->type === self::ALLOCATION_CMP_FLAG)
			? $condition->operator
			: null;
		if ($node->else === null) {
			$this->generateCmpNegativeJumpToLabel($alloc, $labelEndIf, $operator);
			$this->emitComment("then");
			$this->generateThenBlock($node->then, $labelEndIf);
		} else {
			$labelThen = $this->newLabel();
			$this->generateCmpJumpToLabel($alloc, $labelThen, $operator);

			if ($node->else->node !== NodeType::STATEMENT_ELSE_IF) {
				$this->emitComment("else");
			}
			$this->generateThenBlock($node->else, $labelEndIf);
			$this->emitJump('jmp', $labelEndIf);

			$this->emitLabel($labelThen);
			$this->emitComment("then");
			$this->generateThenBlock($node->then);
		}

		if ($isRootIf) {
			$this->emitComment("end if");
			$this->emitLabel($labelEndIf);
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

		$prevStackPos = $this->beginBlock();
			if ($node->node === NodeType::STATEMENT_SCOPE) {
				foreach ($node->statements as $statement) {
					$this->generateStmt($statement);
				}
			} else {
				$this->generateStmt($node);
			}
		$this->endBlock($prevStackPos);
	}

	/**
	 * @param NodeStmtWhileLoop|object $node
	 */
	protected function generateStmtWhileLoop(object $node): void {
		/** @var Node|NodeExpressionBinary $condition */
		$condition = $node->condition;

		$labelBeginWhile = $this->newLabel();
		$labelCondition = $this->newLabel();
		$this->emitComment("while (...)");
		$this->emitJump('jmp', $labelCondition);

		$prevStackPos = $this->beginBlock();
			$this->emitLabel($labelBeginWhile);
			foreach ($node->statements as $statement) {
				$this->generateStmt($statement);
			}

			$this->emitLabel($labelCondition);
			$alloc = $this->generateExpression($condition, true, true);
			if ($alloc->type === self::ALLOCATION_IMMEDIATE) {
				// while (immediate)
				//   ->
				// reg = immediate
				// while (reg)
				// not using zeroAsXor because "while (0)" should not happen
				// after optimizing "always true/always false" conditions
				$alloc = $this->allocateFreeRegisterFrom($alloc);
			}
			$this->deallocateIfRegister($alloc);

			$this->generateCmpJumpToLabel($alloc, $labelBeginWhile, $condition->operator);
			$this->emitComment("end while");
		$this->endBlock($prevStackPos);
	}

	/**
	 * @param NodeStmtForLoop|object $node
	 */
	protected function generateStmtForLoop(object $node): void {
		$labelBeginFor = $this->newLabel();
		$labelCondition = $this->newLabel();
		$labelContinue = $this->newLabel();
		$this->emitComment("for (...)");

		$prevStackPos = $this->beginBlock();
			$identifier = $node->identifier;
			$this->generateExpressionAssignment((object) [
				'node' => NodeType::EXPRESSION_ASSIGNMENT,
				'operator' => '=',
				'left' => $identifier,
				'right' => $node->from,
				'line' => $node->from->line, 'col' => $node->from->col,
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
				$conditionOperator = $node->exclusive ? '>' : '>=';
			} else {
				$step = 1;
				$conditionOperator = $node->exclusive ? '<' : '<=';
			}

			$this->emitJump('jmp', $labelCondition);

			$this->emitLabel($labelBeginFor);
			foreach ($node->statements as $statement) {
				$this->generateStmt($statement);
			}

			$this->emitLabel($labelContinue);
			$allocLeft = $this->generateIncrementVariable($identifier, $step);
			$this->emitLabel($labelCondition);

			$allocRight = $this->generateExpression($node->to, true); // todo: ? disable variable modifications during this
			$alloc = $this->generateComparison($allocLeft, $allocRight, $conditionOperator, true);

			$this->generateCmpJumpToLabel($alloc, $labelBeginFor, $conditionOperator);
			$this->emitComment("end for");
		$this->endBlock($prevStackPos);
	}

	/**
	 * @param NodeDeclVariable|object $node
	 */
	protected function generateDeclVariable(object $node): void {
		$identifier = $node->identifier->name;
		if (isset($this->identifiers[$identifier])) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Variable '{$identifier}' is already declared");
		}
		$this->identifiers[$identifier] = (object) [
			'initScope' => 0,
			'mutable' => $node->mutable,
			'alloc' => $this->allocateStack(self::SIZE_32), // todo: size depending on type
		];
		$this->identifiersStack[] = $identifier;
		$this->emitComment("declare {$identifier}");
	}

	protected function beginBlock(): int {
		$this->scopeNesting++;
		return $this->stackPos;
	}

	/**
	 * remove variables declared during block body, restore stack pointer
	 * @param int $prevStackPos
	 */
	protected function endBlock(int $prevStackPos): void {
		$this->scopeNesting--;
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
		$this->stackPos = $prevStackPos;
	}

	/* protected function debug(string $message): void {
		fwrite(STDERR, $message . "\n");
	} */

	/**
	 * @param NodeStmtExpression|object $node
	 */
	protected function generateStmtExpression(object $node): void {
		$this->generateExpression($node->value, false);
	}

	/**
	 * @param Node|object $node
	 */
	protected function generateStmt(object $node): void {
		if ($node->node === NodeType::STATEMENT_EXPRESSION) {
			$this->generateStmtExpression($node);
		} else if ($node->node === NodeType::EXPRESSION_ASSIGNMENT) {
			$this->generateExpressionAssignment($node);
		} else if ($node->node === NodeType::EXPRESSION_UPDATE) {
			$this->generateExpressionUpdate($node, false);

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
			$this->generateDeclVariable($node);
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

	protected function align16(int $value): int {
		return $value + 16 - $value % 16;
	}

	/**
	 * @param NodeProgram|object $node
	 * @param string $fileName
	 */
	public function generate(object $node, string $fileName): void {
		$this->fileName = $fileName;
		$this->init();

		foreach ($node->statements as $statement) {
			$this->generateStmt($statement);
		}

		// 32 is shadow space
		$stackSize = $this->align16($this->stackPos * 4 + 32);

		echo "format PE64 console 4.0\n\n";

		echo "section '.data' data readable writeable\n";
		echo "    format_i32 db '%d', 10, 0\n";
		// echo "    format_i64 db '%lld', 0\n";
		// echo "    TRUE = 1\n";
		// echo "    FALSE = 0\n";
		echo "    NULL = 0\n";
		echo "\n";

		echo "section '.text' code readable executable\n";
		echo "entry start\n";
		echo "start:\n";
		echo "    mov     rbp, rsp\n";
		echo "    sub     rsp, {$stackSize}\n";
		// echo "    sub rsp, 32\n";
		// echo "    and rsp, -16\n";
		echo "\n";

		$this->emitInstructions();
		echo "\n";

		echo "exit:\n";
		echo "    mov     rsp, rbp\n";
		echo "    xor     rax, rax\n";
		echo "    ret\n";
		echo "\n\n";

		echo "section '.idata' data import readable\n";
		echo "    dd RVA msvcrt.lookup, 0, 0, RVA msvcrt_name, RVA msvcrt.address\n";
		echo "    dd 0, 0, 0, 0, 0\n";
		echo "\n";
		echo "    msvcrt_name db 'msvcrt.dll', 0\n";
		echo "\n";
		echo "    msvcrt.lookup:\n";
		echo "        dq RVA printf_name\n";
		echo "        dq 0\n";
		echo "\n";
		echo "    msvcrt.address:\n";
		echo "        printf dq RVA printf_name\n";
		echo "        dq 0\n";
		echo "\n";
		echo "    printf_name	dw 0\n";
		echo "        db 'printf', 0\n";
	}

}
