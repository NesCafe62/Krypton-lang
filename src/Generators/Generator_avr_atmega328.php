<?php

class Generator_avr_atmega328 implements GeneratorInterface {

	/* * @var int */
	// protected $stackPos;

	/* * @var int */
	// protected $scopeNesting;

	/* * @var array */
	// protected $identifiers;

	/* * @var array */
	// protected $identifiersStack;

	/** @var array */
	protected $allocated;

	/* * @var int */
	// protected $labelIndex;

	/** @var Instruction[] */
	protected $instructions;

	/* * @var int */
	// protected $prevSwappableIndex;

	protected const EMIT_INSTRUCTION = 1;
	protected const EMIT_INSTRUCTION_JUMP = 2;
	protected const EMIT_LABEL = 3;
	protected const EMIT_COMMENT = 4;
	
	// registers
	protected const REGISTER_NONE = 32;
	protected const REGISTER_R0 = 0;
	protected const REGISTER_R1 = 1;
	protected const REGISTER_R2 = 2;
	protected const REGISTER_R3 = 3;
	protected const REGISTER_R4 = 4;
	protected const REGISTER_R5 = 5;
	protected const REGISTER_R6 = 6;
	protected const REGISTER_R7 = 7;

	// instruction argument types
	protected const ARG_NONE = 0;
	protected const ARG_IMMEDIATE = 1;
	protected const ARG_REGISTER = 2;
	protected const ARG_ADDRESS = 3;
	protected const ARG_STACK_8 = 6;
	/* protected const ARG_STACK_64 = 6;
	protected const ARG_STACK_32 = 6 + self::SIZE_32;
	protected const ARG_STACK_16 = 6 + self::SIZE_16;
	protected const ARG_STACK_8 = 6 + self::SIZE_8; */

	// allocation types
	protected const ALLOCATION_IMMEDIATE = 1;
	protected const ALLOCATION_REGISTER = 2;
	protected const ALLOCATION_RESERVED = 4;
	protected const ALLOCATION_CMP_FLAG = 5;
	protected const ALLOCATION_STACK = 6;
	

	protected static $registers = [
		self::REGISTER_R0 => 'r0',
		self::REGISTER_R1 => 'r1',
		self::REGISTER_R2 => 'r2',
		self::REGISTER_R3 => 'r3',
		self::REGISTER_R4 => 'r4',
		self::REGISTER_R5 => 'r5',
		self::REGISTER_R6 => 'r6',
		self::REGISTER_R7 => 'r7',
	];
	
	protected function init(): void {
		/* $this->stackPos = 0;
		$this->scopeNesting = self::ROOT_SCOPE_NESTING;
		$this->identifiers = [];
		$this->identifiersStack = []; */
		$this->allocated = [
			self::REGISTER_R0 => null,
			self::REGISTER_R1 => null,
			self::REGISTER_R2 => null,
			self::REGISTER_R3 => null,
			self::REGISTER_R4 => null,
			self::REGISTER_R5 => null,
			self::REGISTER_R6 => null,
			self::REGISTER_R7 => null,
		];
		$this->instructions = [];
		/* $this->prevSwappableIndex = 0;

		$this->labelIndex = 0; */
	}
	
	protected static $instructionOpCodes = [
		// instruction => [opCode, ...]
		'nop'		=> [0x0000], //          0000 0000 0000 0000
		'sec'		=> [0x9408], //          1001 0100 0000 1000
		'clc'		=> [0x9488], //          1001 0100 1000 1000
		'sen'		=> [0x9428], //          1001 0100 0010 1000
		'cln'		=> [0x94a8], //          1001 0100 1010 1000
		'sez'		=> [0x9418], //          1001 0100 0001 1000
		'clz'		=> [0x9498], //          1001 0100 1001 1000
		'sei'		=> [0x9478], //          1001 0100 0111 1000
		'cli'		=> [0x94f8], //          1001 0100 1111 1000
		'ses'		=> [0x9448], //          1001 0100 0100 1000
		'cls'		=> [0x94c8], //          1001 0100 1100 1000
		'sev'		=> [0x9438], //          1001 0100 0011 1000
		'clv'		=> [0x94b8], //          1001 0100 1011 1000
		'set'		=> [0x9468], //          1001 0100 0110 1000
		'clt'		=> [0x94e8], //          1001 0100 1110 1000
		'seh'		=> [0x9458], //          1001 0100 0101 1000
		'clh'		=> [0x94d8], //          1001 0100 1101 1000
		'sleep'		=> [0x9588], //          1001 0101 1000 1000
		'wdr'		=> [0x95a8], //          1001 0101 1010 1000
		'ijmp'		=> [0x9409], //          1001 0100 0000 1001    (DF_TINY1X)
		'eijmp'		=> [0x9419], //          1001 0100 0001 1001    (DF_NO_EIJMP)
		'icall'		=> [0x9509], //          1001 0101 0000 1001    (DF_TINY1X)
		'eicall'	=> [0x9519], //          1001 0101 0001 1001    (DF_NO_EICALL)
		'ret'		=> [0x9508], //          1001 0101 0000 1000
		'reti'		=> [0x9518], //          1001 0101 0001 1000
		'spm'		=> [0x95e8], //          1001 0101 1110 1000    (DF_NO_SPM)
		'espm'		=> [0x95f8], //          1001 0101 1111 1000    (DF_NO_ESPM)
		'break'		=> [0x9598], //          1001 0101 1001 1000    (DF_NO_BREAK)
		'lpm'		=> [0x95c8], //          1001 0101 1100 1000    (DF_NO_LPM)
		'elpm'		=> [0x95d8], //          1001 0101 1101 1000    (DF_NO_ELPM)
		'bset'		=> [0x9408], // s        1001 0100 0sss 1000
		'bclr'		=> [0x9488], // s        1001 0100 1sss 1000
		'ser'		=> [0xef0f], // Rd       1110 1111 dddd 1111
		'com'		=> [0x9400], // Rd       1001 010d dddd 0000
		'neg'		=> [0x9401], // Rd       1001 010d dddd 0001
		'inc'		=> [0x9403], // Rd       1001 010d dddd 0011
		'dec'		=> [0x940a], // Rd       1001 010d dddd 1010
		'lsr'		=> [0x9406], // Rd       1001 010d dddd 0110
		'ror'		=> [0x9407], // Rd       1001 010d dddd 0111
		'asr'		=> [0x9405], // Rd       1001 010d dddd 0101
		'swap'		=> [0x9402], // Rd       1001 010d dddd 0010
		'push'		=> [0x920f], // Rr       1001 001r rrrr 1111    (DF_TINY1X)
		'pop'		=> [0x900f], // Rd       1001 000d dddd 1111    (DF_TINY1X)
		'tst'		=> [0x2000], // Rd       0010 00dd dddd dddd
		'clr'		=> [0x2400], // Rd       0010 01dd dddd dddd
		'lsl'		=> [0x0c00], // Rd       0000 11dd dddd dddd
		'rol'		=> [0x1c00], // Rd       0001 11dd dddd dddd
		'breq'		=> [0xf001], // k        1111 00kk kkkk k001
		'brne'		=> [0xf401], // k        1111 01kk kkkk k001
		'brcs'		=> [0xf000], // k        1111 00kk kkkk k000
		'brcc'		=> [0xf400], // k        1111 01kk kkkk k000
		'brsh'		=> [0xf400], // k        1111 01kk kkkk k000
		'brlo'		=> [0xf000], // k        1111 00kk kkkk k000
		'brmi'		=> [0xf002], // k        1111 00kk kkkk k010
		'brpl'		=> [0xf402], // k        1111 01kk kkkk k010
		'brge'		=> [0xf404], // k        1111 01kk kkkk k100
		'brlt'		=> [0xf004], // k        1111 00kk kkkk k100
		'brhs'		=> [0xf005], // k        1111 00kk kkkk k101
		'brhc'		=> [0xf405], // k        1111 01kk kkkk k101
		'brts'		=> [0xf006], // k        1111 00kk kkkk k110
		'brtc'		=> [0xf406], // k        1111 01kk kkkk k110
		'brvs'		=> [0xf003], // k        1111 00kk kkkk k011
		'brvc'		=> [0xf403], // k        1111 01kk kkkk k011
		'brie'		=> [0xf007], // k        1111 00kk kkkk k111
		'brid'		=> [0xf407], // k        1111 01kk kkkk k111
		'rjmp'		=> [0xc000], // k        1100 kkkk kkkk kkkk
		'rcall'		=> [0xd000], // k        1101 kkkk kkkk kkkk
		'jmp'		=> [0x940c], // k        1001 010k kkkk 110k + 16k    (DF_NO_JMP)
		'call'		=> [0x940e], // k        1001 010k kkkk 111k + 16k    (DF_NO_JMP)
		'brbs'		=> [0xf000], // s, k     1111 00kk kkkk ksss
		'brbc'		=> [0xf400], // s, k     1111 01kk kkkk ksss
		'add'		=> [0x0c00], // Rd, Rr   0000 11rd dddd rrrr
		'adc'		=> [0x1c00], // Rd, Rr   0001 11rd dddd rrrr
		'sub'		=> [0x1800], // Rd, Rr   0001 10rd dddd rrrr
		'sbc'		=> [0x0800], // Rd, Rr   0000 10rd dddd rrrr
		'and'		=> [0x2000], // Rd, Rr   0010 00rd dddd rrrr
		'or'		=> [0x2800], // Rd, Rr   0010 10rd dddd rrrr
		'eor'		=> [0x2400], // Rd, Rr   0010 01rd dddd rrrr
		'cp'		=> [0x1400], // Rd, Rr   0001 01rd dddd rrrr
		'cpc'		=> [0x0400], // Rd, Rr   0000 01rd dddd rrrr
		'cpse'		=> [0x1000], // Rd, Rr   0001 00rd dddd rrrr
		'mov'		=> [0x2c00], // Rd, Rr   0010 11rd dddd rrrr
		'mul'		=> [0x9c00], // Rd, Rr   1001 11rd dddd rrrr    (DF_NO_MUL)
		'movw'		=> [0x0100], // Rd, Rr   0000 0001 dddd rrrr    (DF_NO_MOVW)
		'muls'		=> [0x0200], // Rd, Rr   0000 0010 dddd rrrr    (DF_NO_MUL)
		'mulsu'		=> [0x0300], // Rd, Rr   0000 0011 0ddd 0rrr    (DF_NO_MUL)
		'fmul'		=> [0x0308], // Rd, Rr   0000 0011 0ddd 1rrr    (DF_NO_MUL)
		'fmuls'		=> [0x0380], // Rd, Rr   0000 0011 1ddd 0rrr    (DF_NO_MUL)
		'fmulsu'	=> [0x0388], // Rd, Rr   0000 0011 1ddd 1rrr    (DF_NO_MUL)
		'adiw'		=> [0x9600], // Rd, K    1001 0110 KKdd KKKK    (DF_TINY1X | DF_AVR8L)
		'sbiw'		=> [0x9700], // Rd, K    1001 0111 KKdd KKKK    (DF_TINY1X | DF_AVR8L)
		'subi'		=> [0x5000], // Rd, K    0101 KKKK dddd KKKK
		'sbci'		=> [0x4000], // Rd, K    0100 KKKK dddd KKKK
		'andi'		=> [0x7000], // Rd, K    0111 KKKK dddd KKKK
		'ori'		=> [0x6000], // Rd, K    0110 KKKK dddd KKKK
		'sbr'		=> [0x6000], // Rd, K    0110 KKKK dddd KKKK
		'cpi'		=> [0x3000], // Rd, K    0011 KKKK dddd KKKK
		'ldi'		=> [0xe000], // Rd, K    1110 KKKK dddd KKKK
		'cbr'		=> [0x7000], // Rd, K    0111 KKKK dddd KKKK ~K
		'sbrc'		=> [0xfc00], // Rr, b    1111 110r rrrr 0bbb
		'sbrs'		=> [0xfe00], // Rr, b    1111 111r rrrr 0bbb
		'bst'		=> [0xfa00], // Rr, b    1111 101d dddd 0bbb
		'bld'		=> [0xf800], // Rd, b    1111 100d dddd 0bbb
		'in'		=> [0xb000], // Rd, P    1011 0PPd dddd PPPP
		'out'		=> [0xb800], // P, Rr    1011 1PPr rrrr PPPP
		'sbic'		=> [0x9900], // P, b     1001 1001 PPPP Pbbb
		'sbis'		=> [0x9b00], // P, b     1001 1011 PPPP Pbbb
		'sbi'		=> [0x9a00], // P, b     1001 1010 PPPP Pbbb
		'cbi'		=> [0x9800], // P, b     1001 1000 PPPP Pbbb
		'lds'		=> [0x9000], // Rd, k    1001 000d dddd 0000 + 16k    (DF_TINY1X | DF_AVR8L)
		'sts'		=> [0x9200], // k, Rr    1001 001d dddd 0000 + 16k    (DF_TINY1X | DF_AVR8L)
		'ld'		=> [0],      // Rd, __   dummy                        (0)
		'st'		=> [0],      // __, Rr   dummy                        (0)
		'ldd'		=> [0],      // Rd, _+q  dummy                        (DF_TINY1X)
		'std'		=> [0],      // _+q, Rr  dummy                        (DF_TINY1X)
		'count'		=> [0],      //                                       (0)
		/*
		'lpm'		=> [0x9004], // Rd, Z    1001 000d dddd 0100    (DF_NO_LPM|DF_NO_LPM_X)
		'lpm'		=> [0x9005], // Rd, Z+   1001 000d dddd 0101    (DF_NO_LPM|DF_NO_LPM_X)
		'elpm'		=> [0x9006], // Rd, Z    1001 000d dddd 0110    (DF_NO_ELPM|DF_NO_ELPM_X)
		'elpm'		=> [0x9007], // Rd, Z+   1001 000d dddd 0111    (DF_NO_ELPM|DF_NO_ELPM_X)
		'ld'		=> [0x900c], // Rd, X    1001 000d dddd 1100    (DF_NO_XREG)
		'ld'		=> [0x900d], // Rd, X+   1001 000d dddd 1101    (DF_NO_XREG)
		'ld'		=> [0x900e], // Rd, -X   1001 000d dddd 1110    (DF_NO_XREG)
		'ld'		=> [0x8008], // Rd, Y    1000 000d dddd 1000    (DF_NO_XREG)
		'ld'		=> [0x9009], // Rd, Y+   1001 000d dddd 1001    (DF_NO_XREG)
		'ld'		=> [0x900a], // Rd, -Y   1001 000d dddd 1010    (DF_NO_XREG)
		'ld'		=> [0x8000], // Rd, Z    1000 000d dddd 0000    (0)
		'ld'		=> [0x9001], // Rd, Z+   1001 000d dddd 0001    (DF_TINY1X)
		'ld'		=> [0x9002], // Rd, -Z   1001 000d dddd 0010    (DF_TINY1X)
		'st'		=> [0x920c], // X, Rr    1001 001d dddd 1100    (DF_NO_XREG)
		'st'		=> [0x920d], // X+, Rr   1001 001d dddd 1101    (DF_NO_XREG)
		'st'		=> [0x920e], // -X, Rr   1001 001d dddd 1110    (DF_NO_XREG)
		'st'		=> [0x8208], // Y, Rr    1000 001d dddd 1000    (DF_NO_XREG)
		'st'		=> [0x9209], // Y+, Rr   1001 001d dddd 1001    (DF_NO_XREG)
		'st'		=> [0x920a], // -Y, Rr   1001 001d dddd 1010    (DF_NO_XREG)
		'st'		=> [0x8200], // Z, Rr    1000 001d dddd 0000    (0)
		'st'		=> [0x9201], // Z+, Rr   1001 001d dddd 0001    (DF_TINY1X)
		'st'		=> [0x9202], // -Z, Rr   1001 001d dddd 0010    (DF_TINY1X)
		'ldd'		=> [0x8008], // Rd, Y+q  10q0 qq0d dddd 1qqq    (DF_TINY1X)
		'ldd'		=> [0x8000], // Rd, Z+q  10q0 qq0d dddd 0qqq    (DF_TINY1X)
		'std'		=> [0x8208], // Y+q, Rr  10q0 qq1r rrrr 1qqq    (DF_TINY1X)
		'std'		=> [0x8200], // Z+q, Rr  10q0 qq1r rrrr 0qqq    (DF_TINY1X)
		'lds'		=> [0xa000], // Rd, k    1010 0kkk dddd kkkk    (DF_TINY1X)
		'sts'		=> [0xa800], // Rd, k    1010 1kkk dddd kkkk    (DF_TINY1X)
		'end'		=> [0], // (0)
		*/
	];
	
	
	/**
	 * @param Allocation|object $alloc
	 * @return array
	 */
	protected function emitInstructionArg(object $alloc): array {
		/* if ($alloc->type === self::ALLOCATION_REGISTER) {
			return [$alloc->value + $alloc->size, self::ARG_REGISTER];
		}
		if ($alloc->type === self::ALLOCATION_IMMEDIATE) {
			return [$alloc->value, self::ARG_IMMEDIATE];
		}
		if ($alloc->type === self::ALLOCATION_STACK) {
			return [$alloc->value * 4, self::ARG_STACK_64 + $alloc->size];
		} */
		throw new CompilerRuntimeException("Invalid emit argument alloc: {$alloc->type}");
	}
	
	/**
	 * @param string $instruction
	 * @param Allocation|null $arg1
	 * @param Allocation|null $arg2
	 * @param Allocation|null $arg3
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
		/* if (
			$instruction === 'mov' &&
			$this->tryFuseMov($instr)
		) {
			return;
		} */
		// $this->checkSwappable($instr);
		$this->instructions[] = $instr;
	}
	
	
	protected function instructionArg(int $arg, int $argType): string {
		/* if ($argType === self::ARG_REGISTER) {
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
		} */
		throw new CompilerRuntimeException("Invalid instruction argument type: {$argType}");
	}
	
	protected function emitInstructions(): void {
		foreach ($this->instructions as $instr) {
			if ($instr->type === self::EMIT_INSTRUCTION) {
				/* if ($instr->instruction === 'lea') {
					$arg1 = self::$registers[$instr->arg1];
					echo "    lea     {$arg1}, [{$instr->text}]\n";
					continue;
				} */
				/* if ($instr->instruction === 'call') {
					echo "    call    [{$instr->text}]\n";
					continue;
				} */
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
	
	
	
	protected function getFreeRegister(): int {
		// for ($index = $this->lastRegIndex; $index < $this->lastRegIndex + self::REGISTER_9; $index++) {
		for ($regIndex = self::REGISTER_R0; $regIndex <= self::REGISTER_R7; $regIndex++) {
			// $regIndex = 1 + $index % self::REGISTER_9;
			if ($this->allocated[$regIndex] === null) {
				return $regIndex;
			}
		}
		return self::REGISTER_NONE;
	}

	protected function deallocateRegister(int $regIndex): void {
		$this->allocated[$regIndex] = null;
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
			// ldi reg, 0
			//   ->
			// clr reg
			$this->emit('clr', $allocRes);
		} else if ($alloc->type === self::ALLOCATION_IMMEDIATE) {
			$this->emit('ldi', $allocRes, $alloc);
		} else if ($alloc->type === self::ALLOCATION_REGISTER) {
			$this->emit('mov', $allocRes, $alloc);
		}
		return $allocRes;
	}
	
	
	
	/**
	 * @param NodeLiteralInt|object $node
	 * @return Allocation
	 */
	protected function generateLiteralInt(object $node): object {
		return (object) [
			'type' => self::ALLOCATION_IMMEDIATE,
			'value' => $node->value,
			// 'size' => self::SIZE_8,
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
			// 'size' => self::SIZE_8,
		];
	}
	
	
	
	/**
	 * @param NodeExpressionBinary|object $node
	 * @param bool $allocAsCmpFlag
	 * @return Allocation
	 */
	protected function generateExpressionBinary(object $node, bool $allocAsCmpFlag = false): object {
		$allocLeft = $this->generateExpression($node->left, true);
		$allocRight = $this->generateExpression($node->right, true);
		
		if ($allocLeft->type !== self::ALLOCATION_REGISTER) {
			$allocLeft = $this->allocateFreeRegisterFrom($allocLeft, true);
			// prev $allocLeft doesn't need deallocation because it's not a register
		}
		
		
		
		throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Operator '{$node->operator}' is not implemented");
	}
	
	
	
	/**
	 * @param NodeStmtExpression|object $node
	 * @param bool $isResultUsed
	 * @param bool $allocAsCmpFlag
	 * @return Allocation
	 */
	protected function generateExpression(object $node, bool $isResultUsed, bool $allocAsCmpFlag = false): object {
		/* if ($node->node === NodeType::EXPRESSION_ASSIGNMENT) {
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
		} */
		$nodeType = Lang::getNodeTypeName($node);
		throw new CompilerRuntimeException("Wrong node type: {$nodeType}");
	}
	
	

	/**
	 * @param Node|object $node
	 */
	protected function generateStmt(object $node): void {
		if ($node->node === NodeType::STATEMENT_EXPRESSION) {
			// $this->generateStmtExpression($node);
		} else if ($node->node === NodeType::EXPRESSION_ASSIGNMENT) {
			// $this->generateExpressionAssignment($node);
		} else if ($node->node === NodeType::EXPRESSION_UPDATE) {
			// $this->generateExpressionUpdate($node, false);

		} else if ($node->node === NodeType::STATEMENT_IO_PRINT) { // todo: temporary io.print
			// $this->generateStmtIOPrint($node);

		} else if ($node->node === NodeType::STATEMENT_RETURN) {
			// $this->generateStmtReturn($node);
		} else if (
			$node->node === NodeType::STATEMENT_IF ||
			$node->node === NodeType::STATEMENT_ELSE_IF
		) {
			// $this->generateStmtIf($node);
		} else if ($node->node === NodeType::STATEMENT_WHILE_LOOP) {
			// $this->generateStmtWhileLoop($node);
		} else if ($node->node === NodeType::STATEMENT_FOR_LOOP) {
			// $this->generateStmtForLoop($node);
		} else if ($node->node === NodeType::DECLARATION_VARIABLE) {
			// $this->generateDeclVariable($node);
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

		foreach ($node->statements as $statement) {
			$this->generateStmt($statement);
		}

		/* echo "format PE64 console 4.0\n\n";

		echo "section '.data' data readable writeable\n";
		echo "    format_i32 db '%d', 10, 0\n";
		echo "    NULL = 0\n";
		echo "\n";

		echo "section '.text' code readable executable\n";
		echo "entry start\n";
		echo "start:\n";
		echo "    mov     rbp, rsp\n";
		echo "    sub     rsp, {$stackSize}\n";
		echo "\n"; */

		$this->emitInstructions();
		echo "\n";

		/* echo "exit:\n";
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
		echo "        db 'printf', 0\n"; */
	}

}