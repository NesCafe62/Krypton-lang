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
	protected const EMIT_INSTRUCTION_LDD_STD = 2;
	protected const EMIT_INSTRUCTION_JUMP = 3;
	protected const EMIT_LABEL = 4;
	protected const EMIT_COMMENT = 5;

	// registers
	protected const REGISTER_NONE = 32;
	protected const REGISTER_R0 = 0;
	protected const REGISTER_R16 = 16;
	protected const REGISTER_R23 = 23;
	protected const REGISTER_R31 = 31;

	// instruction argument types
	protected const ARG_NONE = 0;
	protected const ARG_IMMEDIATE = 1;
	protected const ARG_REGISTER = 2;
	protected const ARG_ADDRESS = 3;
	protected const ARG_STACK_8 = 6;
	// protected const ARG_POINTER_REGISTER = 7; // X|X+|-X Y|Y+|-Y Z|Z+|-Z

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
	// protected const ALLOCATION_POINTER_REGISTER = 7;
	

	/* protected static $registers = [
		self::REGISTER_R0 => 'r0',
		self::REGISTER_R1 => 'r1',
		self::REGISTER_R2 => 'r2',
		self::REGISTER_R3 => 'r3',
		self::REGISTER_R4 => 'r4',
		self::REGISTER_R5 => 'r5',
		self::REGISTER_R6 => 'r6',
		self::REGISTER_R7 => 'r7',
	]; */

	/** @var bool */
	protected $outputHex;

	public function __construct(bool $outputHex = false) {
		$this->outputHex = $outputHex;
	}

	protected function init(): void {
		// $this->stackPos = 0;
		// $this->scopeNesting = self::ROOT_SCOPE_NESTING;
		// $this->identifiers = [];
		// $this->identifiersStack = [];
		$this->allocated = [];
		for ($i = self::REGISTER_R0; $i <= self::REGISTER_R31; $i++) {
			$this->allocated[$i] = null;
		}
		$this->instructions = [];
		/* $this->prevSwappableIndex = 0; */

		// $this->labelIndex = 0;
	}
	
	protected const INSTR_CLC		= 1;
	protected const INSTR_NOP		= 2;
	protected const INSTR_SEC		= 3;
	protected const INSTR_SEN		= 4;
	protected const INSTR_CLN		= 5;
	protected const INSTR_SEZ		= 6;
	protected const INSTR_CLZ		= 7;
	protected const INSTR_SEI		= 8;
	protected const INSTR_CLI		= 9;
	protected const INSTR_SES		= 10;
	protected const INSTR_CLS		= 11;
	protected const INSTR_SEV		= 12;
	protected const INSTR_CLV		= 13;
	protected const INSTR_SET		= 14;
	protected const INSTR_CLT		= 15;
	protected const INSTR_SEH		= 16;
	protected const INSTR_CLH		= 17;
	protected const INSTR_SLEEP		= 18;
	protected const INSTR_WDR		= 19;
	protected const INSTR_IJMP		= 20;
	protected const INSTR_EIJMP		= 21;
	protected const INSTR_ICALL		= 22;
	protected const INSTR_EICALL	= 23;
	protected const INSTR_RET		= 24;
	protected const INSTR_RETI		= 25;
	protected const INSTR_SPM		= 26;
	// protected const INSTR_ESPM		= 27;
	protected const INSTR_BREAK		= 28;
	protected const INSTR_LPM		= 29;
	protected const INSTR_ELPM		= 30;
	protected const INSTR_BSET		= 31;
	protected const INSTR_BCLR		= 32;
	protected const INSTR_SER		= 33;
	protected const INSTR_COM		= 34;
	protected const INSTR_NEG		= 35;
	protected const INSTR_INC		= 36;
	protected const INSTR_DEC		= 37;
	protected const INSTR_LSR		= 38;
	protected const INSTR_ROR		= 39;
	protected const INSTR_ASR		= 40;
	protected const INSTR_SWAP		= 41;
	protected const INSTR_PUSH		= 42;
	protected const INSTR_POP		= 43;
	protected const INSTR_TST		= 44;
	protected const INSTR_CLR		= 45;
	protected const INSTR_LSL		= 46;
	protected const INSTR_ROL		= 47;
	protected const INSTR_BREQ		= 48;
	protected const INSTR_BRNE		= 49;
	protected const INSTR_BRCS		= 50;
	protected const INSTR_BRCC		= 51;
	protected const INSTR_BRSH		= 52;
	protected const INSTR_BRLO		= 53;
	protected const INSTR_BRMI		= 54;
	protected const INSTR_BRPL		= 55;
	protected const INSTR_BRGE		= 56;
	protected const INSTR_BRLT		= 57;
	protected const INSTR_BRHS		= 58;
	protected const INSTR_BRHC		= 59;
	protected const INSTR_BRTS		= 60;
	protected const INSTR_BRTC		= 61;
	protected const INSTR_BRVS		= 62;
	protected const INSTR_BRVC		= 63;
	protected const INSTR_BRIE		= 64;
	protected const INSTR_BRID		= 65;
	protected const INSTR_RJMP		= 66;
	protected const INSTR_RCALL		= 67;
	protected const INSTR_JMP		= 68;
	protected const INSTR_CALL		= 69;
	protected const INSTR_BRBS		= 70;
	protected const INSTR_BRBC		= 71;
	protected const INSTR_ADD		= 72;
	protected const INSTR_ADC		= 73;
	protected const INSTR_SUB		= 74;
	protected const INSTR_SBC		= 75;
	protected const INSTR_AND		= 76;
	protected const INSTR_OR		= 77;
	protected const INSTR_EOR		= 78;
	protected const INSTR_CPC		= 79;
	protected const INSTR_CPSE		= 80;
	protected const INSTR_MOV		= 81;
	protected const INSTR_MUL		= 82;
	protected const INSTR_MOVW		= 83;
	protected const INSTR_MULS		= 84;
	protected const INSTR_MULSU		= 85;
	protected const INSTR_FMUL		= 86;
	protected const INSTR_FMULS		= 87;
	protected const INSTR_FMULSU	= 88;
	protected const INSTR_ADIW		= 89;
	protected const INSTR_SBIW		= 90;
	protected const INSTR_SUBI		= 91;
	protected const INSTR_SBCI		= 92;
	protected const INSTR_ANDI		= 93;
	protected const INSTR_ORI		= 94;
	protected const INSTR_SBR		= 95;
	protected const INSTR_CPI		= 96;
	protected const INSTR_LDI		= 97;
	protected const INSTR_CBR		= 98;
	protected const INSTR_SBRC		= 99;
	protected const INSTR_SBRS		= 100;
	protected const INSTR_BST		= 101;
	protected const INSTR_BLD 		= 102;
	protected const INSTR_IN 		= 103;
	protected const INSTR_OUT 		= 104;
	protected const INSTR_SBIC 		= 105;
	protected const INSTR_SBIS 		= 106;
	protected const INSTR_SBI 		= 107;
	protected const INSTR_CBI 		= 108;
	protected const INSTR_LDS 		= 109;
	protected const INSTR_STS 		= 110;
	protected const INSTR_LD 		= 111;
	protected const INSTR_ST 		= 112;
	protected const INSTR_LDD_Y		= 113;
	protected const INSTR_LDD_Z		= 114;
	protected const INSTR_STD_Y		= 115;
	protected const INSTR_STD_Z		= 116;

	// https://github.com/Ro5bert/avra/blob/afdbda414ba3c062b528d74a3f2139e2418b04ff/src/mnemonic.c#L39
	// https://sourceware.org/binutils/docs/as/AVR-Opcodes.html

	// (*r - source, *d - destination)
	// Rr, Rd   any register
	// Dr, Dd   `ldi` register (r16..r31)
	// Vr, Vd   `movw` even register (r0, r2, ..., r28, r30)
	// Ar, Ad   `fmul` register (r16..r23)
	// w        `adiw` register (r24, r26, r28, r30)
	// e        pointer registers (X|X+|-X | Y|Y+|-Y, Z|Z+|-Z)
	// z        Z pointer register (Z | Z+) (`lpm`, `elpm`)
	// M        immediate value 0..255
	// n        immediate value 0..255 (n = ~M). Relocation impossible
	// s        immediate value 0..7
	// P        port address value 0..63 (`in`, `out`)
	// p        port address value 0..31 (`cbi`, `sbi`, `sbic`, `sbis`)
	// K        immediate value 0..63 (`adiw`, `sbiw`)
	// i        immediate value
	// f        signed pc relative offset -64..63
	// F        signed pc relative offset -2048..2047
	// h        absolute code address (`call`, `jmp`)
	// .        use this opcode entry if no parameters, else use next opcode entry

	protected static $instructionsData = [
		// instructionType => [opCode, instruction]
		self::INSTR_CLC			=> [0x9488, 'clc'],		//          1001 0100 1000 1000
		self::INSTR_NOP			=> [0x0000, 'nop'],		//          0000 0000 0000 0000
		self::INSTR_SEC			=> [0x9408, 'sec'],		//          1001 0100 0000 1000
		self::INSTR_SEN			=> [0x9428, 'sen'],		//          1001 0100 0010 1000
		self::INSTR_CLN			=> [0x94a8, 'cln'],		//          1001 0100 1010 1000
		self::INSTR_SEZ			=> [0x9418, 'sez'],		//          1001 0100 0001 1000
		self::INSTR_CLZ			=> [0x9498, 'clz'],		//          1001 0100 1001 1000
		self::INSTR_SEI			=> [0x9478, 'sei'],		//          1001 0100 0111 1000
		self::INSTR_CLI			=> [0x94f8, 'cli'],		//          1001 0100 1111 1000
		self::INSTR_SES			=> [0x9448, 'ses'],		//          1001 0100 0100 1000
		self::INSTR_CLS			=> [0x94c8, 'cls'],		//          1001 0100 1100 1000
		self::INSTR_SEV			=> [0x9438, 'sev'],		//          1001 0100 0011 1000
		self::INSTR_CLV			=> [0x94b8, 'clv'],		//          1001 0100 1011 1000
		self::INSTR_SET			=> [0x9468, 'set'],		//          1001 0100 0110 1000
		self::INSTR_CLT			=> [0x94e8, 'clt'],		//          1001 0100 1110 1000
		self::INSTR_SEH			=> [0x9458, 'seh'],		//          1001 0100 0101 1000
		self::INSTR_CLH			=> [0x94d8, 'clh'],		//          1001 0100 1101 1000
		self::INSTR_SLEEP		=> [0x9588, 'sleep'],	//          1001 0101 1000 1000
		self::INSTR_WDR			=> [0x95a8, 'wdr'],		//          1001 0101 1010 1000
		self::INSTR_IJMP		=> [0x9409, 'ijmp'],	//          1001 0100 0000 1001    (DF_TINY1X)
		self::INSTR_EIJMP		=> [0x9419, 'eijmp'],	//          1001 0100 0001 1001    (DF_NO_EIJMP)
		self::INSTR_ICALL		=> [0x9509, 'icall'],	//          1001 0101 0000 1001    (DF_TINY1X)
		self::INSTR_EICALL		=> [0x9519, 'eicall'],	//          1001 0101 0001 1001    (DF_NO_EICALL)
		self::INSTR_RET			=> [0x9508, 'ret'],		//          1001 0101 0000 1000
		self::INSTR_RETI		=> [0x9518, 'reti'],	//          1001 0101 0001 1000
		self::INSTR_SPM			=> [0x95e8, 'spm'],		//          1001 0101 1110 1000    (DF_NO_SPM)
		/* ?
		self::INSTR_ESPM		=> [0x95f8, 'espm'],	//          1001 0101 1111 1000    (DF_NO_ESPM)
		*/
		self::INSTR_BREAK		=> [0x9598, 'break'],	//          1001 0101 1001 1000    (DF_NO_BREAK)
		self::INSTR_LPM			=> [0x95c8, 'lpm'],		// .        1001 0101 1100 1000    (DF_NO_LPM)
		//						=> [0x9004, 'lpm'],		// Rd, z    1001 000d dddd 010+    (DF_NO_LPM|DF_NO_LPM_X)
		self::INSTR_ELPM		=> [0x95d8, 'elpm'],	// .        1001 0101 1101 1000    (DF_NO_ELPM)
		//						=> [0x9006, 'elpm'],	// Rd, z    1001 000d dddd 011+    (DF_NO_ELPM|DF_NO_ELPM_X)
		self::INSTR_BSET		=> [0x9408, 'bset'],	// s        1001 0100 0SSS 1000
		self::INSTR_BCLR		=> [0x9488, 'bclr'],	// s        1001 0100 1SSS 1000
		self::INSTR_SER			=> [0xef0f, 'ser'],		// Dd       1110 1111 dddd 1111
		self::INSTR_COM			=> [0x9400, 'com'],		// Rd       1001 010d dddd 0000
		self::INSTR_NEG			=> [0x9401, 'neg'],		// Rd       1001 010d dddd 0001
		self::INSTR_INC			=> [0x9403, 'inc'],		// Rd       1001 010d dddd 0011
		self::INSTR_DEC			=> [0x940a, 'dec'],		// Rd       1001 010d dddd 1010
		self::INSTR_LSR			=> [0x9406, 'lsr'],		// Rd       1001 010d dddd 0110
		self::INSTR_ROR			=> [0x9407, 'ror'],		// Rd       1001 010d dddd 0111
		self::INSTR_ASR			=> [0x9405, 'asr'],		// Rd       1001 010d dddd 0101
		self::INSTR_SWAP		=> [0x9402, 'swap'],	// Rd       1001 010d dddd 0010
		self::INSTR_PUSH		=> [0x920f, 'push'],	// Rr       1001 001r rrrr 1111    (DF_TINY1X)
		self::INSTR_POP			=> [0x900f, 'pop'],		// Rd       1001 000d dddd 1111    (DF_TINY1X)
		self::INSTR_TST			=> [0x2000, 'tst'],		// Rd       0010 00rd dddd rrrr r=d
		self::INSTR_CLR			=> [0x2400, 'clr'],		// Rd       0010 01rd dddd rrrr r=d
		self::INSTR_LSL			=> [0x0c00, 'lsl'],		// Rd       0000 11rd dddd rrrr r=d
		self::INSTR_ROL			=> [0x1c00, 'rol'],		// Rd       0001 11rd dddd rrrr r=d
		self::INSTR_BREQ		=> [0xf001, 'breq'],	// f        1111 00ff ffff f001
		self::INSTR_BRNE		=> [0xf401, 'brne'],	// f        1111 01ff ffff f001
		self::INSTR_BRCS		=> [0xf000, 'brcs'],	// f        1111 00ff ffff f000
		self::INSTR_BRCC		=> [0xf400, 'brcc'],	// f        1111 01ff ffff f000
		self::INSTR_BRSH		=> [0xf400, 'brsh'],	// f        1111 01ff ffff f000
		self::INSTR_BRLO		=> [0xf000, 'brlo'],	// f        1111 00ff ffff f000
		self::INSTR_BRMI		=> [0xf002, 'brmi'],	// f        1111 00ff ffff f010
		self::INSTR_BRPL		=> [0xf402, 'brpl'],	// f        1111 01ff ffff f010
		self::INSTR_BRGE		=> [0xf404, 'brge'],	// f        1111 01ff ffff f100
		self::INSTR_BRLT		=> [0xf004, 'brlt'],	// f        1111 00ff ffff f100
		self::INSTR_BRHS		=> [0xf005, 'brhs'],	// f        1111 00ff ffff f101
		self::INSTR_BRHC		=> [0xf405, 'brhc'],	// f        1111 01ff ffff f101
		self::INSTR_BRTS		=> [0xf006, 'brts'],	// f        1111 00ff ffff f110
		self::INSTR_BRTC		=> [0xf406, 'brtc'],	// f        1111 01ff ffff f110
		self::INSTR_BRVS		=> [0xf003, 'brvs'],	// f        1111 00ff ffff f011
		self::INSTR_BRVC		=> [0xf403, 'brvc'],	// f        1111 01ff ffff f011
		self::INSTR_BRIE		=> [0xf007, 'brie'],	// f        1111 00ff ffff f111
		self::INSTR_BRID		=> [0xf407, 'brid'],	// f        1111 01ff ffff f111
		self::INSTR_RJMP		=> [0xc000, 'rjmp'],	// F        1100 FFFF FFFF FFFF
		self::INSTR_RCALL		=> [0xd000, 'rcall'],	// F        1101 FFFF FFFF FFFF
		self::INSTR_JMP			=> [0x940c, 'jmp'],		// h        1001 010h hhhh 110h + 16h    (DF_NO_JMP)
		self::INSTR_CALL		=> [0x940e, 'call'],	// h        1001 010h hhhh 111h + 16h    (DF_NO_JMP)
		self::INSTR_BRBS		=> [0xf000, 'brbs'],	// s, f     1111 00ff ffff fsss
		self::INSTR_BRBC		=> [0xf400, 'brbc'],	// s, f     1111 01ff ffff fsss
		self::INSTR_ADD			=> [0x0c00, 'add'],		// Rd, Rr   0000 11rd dddd rrrr
		self::INSTR_ADC			=> [0x1c00, 'adc'],		// Rd, Rr   0001 11rd dddd rrrr
		self::INSTR_SUB			=> [0x1800, 'sub'],		// Rd, Rr   0001 10rd dddd rrrr
		self::INSTR_SBC			=> [0x0800, 'sbc'],		// Rd, Rr   0000 10rd dddd rrrr
		self::INSTR_AND			=> [0x2000, 'and'],		// Rd, Rr   0010 00rd dddd rrrr
		self::INSTR_OR			=> [0x2800, 'or'],		// Rd, Rr   0010 10rd dddd rrrr
		self::INSTR_EOR			=> [0x2400, 'eor'],		// Rd, Rr   0010 01rd dddd rrrr
		self::INSTR_CPC			=> [0x0400, 'cpc'],		// Rd, Rr   0000 01rd dddd rrrr
		self::INSTR_CPSE		=> [0x1000, 'cpse'],	// Rd, Rr   0001 00rd dddd rrrr
		self::INSTR_MOV			=> [0x2c00, 'mov'],		// Rd, Rr   0010 11rd dddd rrrr
		self::INSTR_MUL			=> [0x9c00, 'mul'],		// Rd, Rr   1001 11rd dddd rrrr    (DF_NO_MUL)
		self::INSTR_MOVW		=> [0x0100, 'movw'],	// Vd, Vr   0000 0001 dddd rrrr    (DF_NO_MOVW)
		self::INSTR_MULS		=> [0x0200, 'muls'],	// Dd, Dr   0000 0010 dddd rrrr    (DF_NO_MUL)
		self::INSTR_MULSU		=> [0x0300, 'mulsu'],	// Ad, Ar   0000 0011 0ddd 0rrr    (DF_NO_MUL)
		self::INSTR_FMUL		=> [0x0308, 'fmul'],	// Ad, Ar   0000 0011 0ddd 1rrr    (DF_NO_MUL)
		self::INSTR_FMULS		=> [0x0380, 'fmuls'],	// Ad, Ar   0000 0011 1ddd 0rrr    (DF_NO_MUL)
		self::INSTR_FMULSU		=> [0x0388, 'fmulsu'],	// Ad, Ar   0000 0011 1ddd 1rrr    (DF_NO_MUL)
		self::INSTR_ADIW		=> [0x9600, 'adiw'],	// w, K     1001 0110 KKww KKKK    (DF_TINY1X | DF_AVR8L)
		self::INSTR_SBIW		=> [0x9700, 'sbiw'],	// w, K     1001 0111 KKww KKKK    (DF_TINY1X | DF_AVR8L)
		self::INSTR_SUBI		=> [0x5000, 'subi'],	// Dd, M    0101 MMMM dddd MMMM
		self::INSTR_SBCI		=> [0x4000, 'sbci'],	// Dd, M    0100 MMMM dddd MMMM
		self::INSTR_ANDI		=> [0x7000, 'andi'],	// Dd, M    0111 MMMM dddd MMMM
		self::INSTR_ORI			=> [0x6000, 'ori'],		// Dd, M    0110 MMMM dddd MMMM
		self::INSTR_SBR			=> [0x6000, 'sbr'],		// Dd, M    0110 MMMM dddd MMMM
		self::INSTR_CPI			=> [0x3000, 'cpi'],		// Dd, M    0011 MMMM dddd MMMM
		self::INSTR_LDI			=> [0xe000, 'ldi'],		// Dd, M    1110 MMMM dddd MMMM
		self::INSTR_CBR			=> [0x7000, 'cbr'],		// Dd, n    0111 nnnn dddd nnnn (n = ~M)
		self::INSTR_SBRC		=> [0xfc00, 'sbrc'],	// Rr, s    1111 110r rrrr 0sss
		self::INSTR_SBRS		=> [0xfe00, 'sbrs'],	// Rr, s    1111 111r rrrr 0sss
		self::INSTR_BST			=> [0xfa00, 'bst'],		// Rd, s    1111 101d dddd 0sss
		self::INSTR_BLD			=> [0xf800, 'bld'],		// Rd, s    1111 100d dddd 0sss
		self::INSTR_IN			=> [0xb000, 'in'],		// Rd, P    1011 0PPd dddd PPPP
		self::INSTR_OUT			=> [0xb800, 'out'],		// P, Rr    1011 1PPr rrrr PPPP
		self::INSTR_SBIC		=> [0x9900, 'sbic'],	// p, s     1001 1001 pppp psss
		self::INSTR_SBIS		=> [0x9b00, 'sbis'],	// p, s     1001 1011 pppp psss
		self::INSTR_SBI			=> [0x9a00, 'sbi'],		// p, s     1001 1010 pppp psss
		self::INSTR_CBI			=> [0x9800, 'cbi'],		// p, s     1001 1000 pppp psss
		self::INSTR_LDS			=> [0x9000, 'lds'],		// Rd, i    1001 000d dddd 0000 + 16i    (DF_TINY1X | DF_AVR8L)
		self::INSTR_STS			=> [0x9200, 'sts'],		// i, Rd    1001 001d dddd 0000 + 16i    (DF_TINY1X | DF_AVR8L)  (i, Rr?)

		/*
		self::INSTR_LDS_AVR8L	=> [0xa000, 'lds'],		// Rd, k    1010 0kkk dddd kkkk    (DF_TINY1X)
		self::INSTR_STS_AVR8L	=> [0xa800, 'sts'],		// Rd, k    1010 1kkk dddd kkkk    (DF_TINY1X)
		*/

		//                                                 X: e=11-+, Y: e=10-+, Z: e=00-+
		self::INSTR_LD			=> [0x8000, 'ld'],		// Rd, e    100! 000d dddd eeee    (DF_NO_XREG)
		self::INSTR_ST			=> [0x8200, 'st'],		// e, Rd    100! 001d dddd eeee    (DF_NO_XREG)  (e, Rr?)

		self::INSTR_LDD_Y		=> [0x8008, 'ldd'],		// Rd, Y+q  10q0 qq0d dddd 1qqq    (DF_TINY1X)
		self::INSTR_LDD_Z		=> [0x8000, 'ldd'],		// Rd, Z+q  10q0 qq0d dddd 0qqq    (DF_TINY1X)
		self::INSTR_STD_Y       => [0x8208, 'std'],		// Y+q, Rd  10q0 qq1d dddd 1qqq    (DF_TINY1X)  (b, Rr?)
		self::INSTR_STD_Z       => [0x8200, 'std'],		// Z+q, Rd  10q0 qq1d dddd 0qqq    (DF_TINY1X)  (b, Rr?)

		// 'count'		=> [0], // (0)

		/*
		self::INSTR_LD			=> [0x900c], // Rd, X    1001 000d dddd 1100	(DF_NO_XREG)
		self::INSTR_LD			=> [0x900d], // Rd, X+   1001 000d dddd 1101	(DF_NO_XREG)
		self::INSTR_LD			=> [0x900e], // Rd, -X   1001 000d dddd 1110	(DF_NO_XREG)
		self::INSTR_LD			=> [0x8008], // Rd, Y    1000 000d dddd 1000	(DF_NO_XREG)
		self::INSTR_LD			=> [0x9009], // Rd, Y+   1001 000d dddd 1001	(DF_NO_XREG)
		self::INSTR_LD			=> [0x900a], // Rd, -Y   1001 000d dddd 1010	(DF_NO_XREG)
		self::INSTR_LD			=> [0x8000], // Rd, Z    1000 000d dddd 0000	(0)
		self::INSTR_LD			=> [0x9001], // Rd, Z+   1001 000d dddd 0001	(DF_TINY1X)
		self::INSTR_LD			=> [0x9002], // Rd, -Z   1001 000d dddd 0010	(DF_TINY1X)
		self::INSTR_ST			=> [0x920c], // X, Rr    1001 001d dddd 1100	(DF_NO_XREG)
		self::INSTR_ST			=> [0x920d], // X+, Rr   1001 001d dddd 1101	(DF_NO_XREG)
		self::INSTR_ST			=> [0x920e], // -X, Rr   1001 001d dddd 1110	(DF_NO_XREG)
		self::INSTR_ST			=> [0x8208], // Y, Rr    1000 001d dddd 1000	(DF_NO_XREG)
		self::INSTR_ST			=> [0x9209], // Y+, Rr   1001 001d dddd 1001	(DF_NO_XREG)
		self::INSTR_ST			=> [0x920a], // -Y, Rr   1001 001d dddd 1010	(DF_NO_XREG)
		self::INSTR_ST			=> [0x8200], // Z, Rr    1000 001d dddd 0000	(0)
		self::INSTR_ST			=> [0x9201], // Z+, Rr   1001 001d dddd 0001	(DF_TINY1X)
		self::INSTR_ST			=> [0x9202], // -Z, Rr   1001 001d dddd 0010	(DF_TINY1X) */
	];

	protected const REG_X	= 0b1100; // X	...1 .... .... [1100]
	protected const REG_XP	= 0b1101; // X+	...1 .... .... [1101]
	protected const REG_XM	= 0b1110; // -X	...1 .... .... [1110]
	protected const REG_Y	= 0b1000; // Y	...0 .... .... [1000]
	protected const REG_YP	= 0b1001; // Y+	...1 .... .... [1001]
	protected const REG_YM	= 0b1010; // -Y	...1 .... .... [1010]
	protected const REG_Z	= 0b0000; // Z	...0 .... .... [0000]
	protected const REG_ZP	= 0b0001; // Z+	...1 .... .... [0001]
	protected const REG_ZM	= 0b0010; // -Z	...1 .... .... [0010]

	// REG_Y_LDD = REG_Y              //	.... .... .... [1...]
	// REG_Z_LDD = REG_Z              //	.... .... .... [0...]

	// REG_Z_LPM = REG_Z              //	.... .... .... [...0]
	// REG_ZP_LPM = REG_ZP            //	.... .... .... [...1]

	/**
	 * @param Allocation|object $alloc
	 * @return array
	 */
	protected function emitInstructionArg(object $alloc): array {
		if ($alloc->type === self::ALLOCATION_REGISTER) {
			return [$alloc->value, /* + $alloc->size */ self::ARG_REGISTER];
		}
		if ($alloc->type === self::ALLOCATION_IMMEDIATE) {
			return [$alloc->value, self::ARG_IMMEDIATE];
		}
		/* if ($alloc->type === self::ALLOCATION_POINTER_REGISTER) {
			return [$alloc->value, self::ARG_POINTER_REGISTER];
		} */
		/* if ($alloc->type === self::ALLOCATION_STACK) {
			return [$alloc->value, self::ARG_STACK_8 + $alloc->size];
		} */
		throw new CompilerRuntimeException("Invalid emit argument alloc: {$alloc->type}");
	}
	
	/**
	 * @param int $instructionType
	 * @param Allocation|null $arg1
	 * @param Allocation|null $arg2
	 * @param Allocation|null $arg3
	 */
	protected function emit(int $instructionType, $arg1 = null, $arg2 = null, $arg3 = null): void {
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
			'instructionType' => $instructionType,
			'arg1' => $arg1Value, 'arg1Type' => $arg1Type,
			'arg2' => $arg2Value, 'arg2Type' => $arg2Type,
			'arg3' => $arg3Value, 'arg3Type' => $arg3Type,
			'text' => '',
			'nArgs' => $nArgs,
		];
		/* if (
			$instructionType === self::INSTR_MOV &&
			$this->tryFuseMov($instr)
		) {
			return;
		} */
		// $this->checkSwappable($instr);
		$this->instructions[] = $instr;
	}

	protected function emitJump(int $instructionType, string $label): void {
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION_JUMP,
			'instructionType' => $instructionType,
			'arg1' => 0, 'arg1Type' => self::ARG_NONE,
			'arg2' => 0, 'arg2Type' => self::ARG_NONE,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => $label,
			'nArgs' => 0,
		];
	}

	protected function emitLDD(int $register, int $pointerReg, int $address): void {
		if ($pointerReg !== self::REG_Y && $pointerReg !== self::REG_Z) {
			throw new CompilerRuntimeException("LDD pointer register need to be `REG_Y` or `REG_Z`");
		}
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION_LDD_STD,
			'instructionType' => ($pointerReg === self::REG_Y) ? self::INSTR_LDD_Y : self::INSTR_LDD_Z,
			'arg1' => $register, 'arg1Type' => self::ARG_REGISTER,
			'arg2' => $address, 'arg2Type' => self::ARG_ADDRESS,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => '',
			'nArgs' => 0,
		];
	}

	protected function emitSTD(int $register, int $pointerReg, int $address): void {
		if ($pointerReg !== self::REG_Y && $pointerReg !== self::REG_Z) {
			throw new CompilerRuntimeException("STD pointer register need to be `REG_Y` or `REG_Z`");
		}
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION_LDD_STD,
			'instructionType' => ($pointerReg === self::REG_Y) ? self::INSTR_STD_Y : self::INSTR_STD_Z,
			'arg1' => $address, 'arg1Type' => self::ARG_ADDRESS,
			'arg2' => $register, 'arg2Type' => self::ARG_REGISTER,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => '',
			'nArgs' => 0,
		];
	}

	protected function emitCall(string $name): void {
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION,
			'instructionType' => self::INSTR_CALL,
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
			'instructionType' => 0,
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
			'instructionType' => 0,
			'arg1' => 0, 'arg1Type' => self::ARG_NONE,
			'arg2' => 0, 'arg2Type' => self::ARG_NONE,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => $comment,
			'nArgs' => 0,
		];
	}

	protected function instructionArg(int $arg, int $argType): string {
		if ($argType === self::ARG_REGISTER) {
			return 'r' . (string) $arg;
		}
		if ($argType === self::ARG_IMMEDIATE) {
			return (string) $arg;
		}
		/* if ($argType === self::ARG_STACK_64) {
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
				$instruction = self::$instructionsData[$instr->instructionType][1];
				if ($instr->nArgs === 0) {
					echo "    {$instruction}\n";
					continue;
				}
				$arg1 = $this->instructionArg($instr->arg1, $instr->arg1Type);
				echo '    ' . str_pad($instruction, 7) . " {$arg1}";
				if ($instr->nArgs >= 2) {
					$arg2 = $this->instructionArg($instr->arg2, $instr->arg2Type);
					echo ", {$arg2}";
					if ($instr->nArgs >= 3) {
						$arg3 = $this->instructionArg($instr->arg3, $instr->arg3Type);
						echo ", {$arg3}";
					}
				}
				echo "\n";
			} else if ($instr->type === self::EMIT_INSTRUCTION_LDD_STD) {
				$instruction = self::$instructionsData[$instr->instructionType][1];
				echo '    ' . str_pad($instruction, 7);
				if ($instr->instructionType === self::INSTR_LDD_Y) {
					$arg1 = $this->instructionArg($instr->arg1, $instr->arg1Type);
					echo " {$arg1}, Y+{$instr->arg2}";
				} else if ($instr->instructionType === self::INSTR_LDD_Z) {
					$arg1 = $this->instructionArg($instr->arg1, $instr->arg1Type);
					echo " {$arg1}, Z+{$instr->arg2}";
				} else if ($instr->instructionType === self::INSTR_STD_Y) {
					$arg2 = $this->instructionArg($instr->arg2, $instr->arg2Type);
					echo " Y+{$instr->arg1}, {$arg2}";
				} else if ($instr->instructionType === self::INSTR_STD_Z) {
					$arg2 = $this->instructionArg($instr->arg2, $instr->arg2Type);
					echo " Z+{$instr->arg1}, {$arg2}";
				} else {
					throw new CompilerRuntimeException("Unexpected instruction type: {$instr->instructionType}");
				}
				echo "\n";
			} else if ($instr->type === self::EMIT_INSTRUCTION_JUMP) {
				$instruction = self::$instructionsData[$instr->instructionType][1];
				echo '    ' . str_pad($instruction, 7) . " {$instr->text}\n";
			} else if ($instr->type === self::EMIT_LABEL) {
				echo "{$instr->text}:\n";
			} else if ($instr->type === self::EMIT_COMMENT) {
				echo "                                ; {$instr->text}\n";
			}
		}
	}

	protected function uint16ToHex(int $value): string {
		return sprintf('%02X', $value);
	}

	protected function uint32ToHex(int $value, bool $littleEndian = false): string {
		if ($littleEndian) {
			return (
				sprintf('%02X', $value & 0xFF) .
				sprintf('%02X', $value >> 8)
			);
		}
		return sprintf('%04X', $value);
	}
	
	protected function emitInstructionsHex(): void {
		// https://en.wikipedia.org/wiki/Intel_HEX
		// : - start code
		// L - byte count
		// a - address
		// t - record type
		// d - data
		// c - checksum
		// : LL aaaa tt dddddd... cc
		// : 10 0100 00 214601360121470136007EFE09D21901 40
		// : 10 0110 00 2146017E17C20001FF5F160021480119 28
		// : 10 0120 00 194E79234623965778239EDA3F01B2CA A7
		// : 10 0130 00 3F0156702B5E712B722B732146013421 C7
		// : 00 0000 01 FF

		$labels = [];
		$address = 0;
		foreach ($this->instructions as $instr) {
			if ($instr->type <= self::EMIT_INSTRUCTION_JUMP) { // EMIT_INSTRUCTION, EMIT_INSTRUCTION_LDD_STD, EMIT_INSTRUCTION_JUMP
				if (
					$instr->instructionType === self::INSTR_JMP ||
					$instr->instructionType === self::INSTR_CALL ||
					$instr->instructionType === self::INSTR_LDS ||
					$instr->instructionType === self::INSTR_STS
				) {
					$address += 2;
					continue;
				}
				$address++;
			} else if ($instr->type === self::EMIT_LABEL) {
				if (isset($labels[$instr->text])) {
					throw new CompilerRuntimeException("Duplicate label '{$instr->text}'");
				}
				$labels[$instr->text] = $address;
			}
		}

		$buffer = '';
		$length = 0;
		$crc = 0;
		$address = 0;
		foreach ($this->instructions as $instr) {
			if ($instr->type <= self::EMIT_INSTRUCTION_JUMP) { // EMIT_INSTRUCTION, EMIT_INSTRUCTION_LDD_STD, EMIT_INSTRUCTION_JUMP
				$isLong = false;
				if (!isset(self::$instructionsData[$instr->instructionType])) {
					throw new CompilerRuntimeException("Unknown instruction type '{$instr->instructionType}'");
				}
				[$opCode, $instruction] = self::$instructionsData[$instr->instructionType];
				$opCode2 = 0;
				if ($instr->instructionType <= self::INSTR_BREAK) {
					// zero args, $opCode is already complete

				} else if (
					$instr->instructionType === self::INSTR_LPM ||
					$instr->instructionType === self::INSTR_ELPM
				) {
					if ($instr->nArgs === 2) {
						// Rd, z    .... ...d dddd ...+
						if ($instr->arg2 !== self::REG_Z && $instr->arg2 !== self::REG_ZP) {
							throw new CompilerRuntimeException("Instruction '{$instruction}' second argument need to be `REG_Z` or `REG_ZP`");
						}
						$opCode =
							(($instr->instructionType === self::INSTR_LPM) ? 0x9004 : 0x9006) |
							($instr->arg1 << 4) |
							$instr->arg2;
					} else if ($instr->nArgs !== 0) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' needs 0 or 2 arguments");
					}
					// else zero args, $opCode is already complete

				} else if (
					$instr->instructionType === self::INSTR_BSET ||
					$instr->instructionType === self::INSTR_BCLR
				) {
					// S        .... .... .SSS ....
					if ($instr->arg1 < 0 || $instr->arg1 > 7) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' argument is out of range 0..7");
					}
					$opCode |= $instr->arg1 << 4;

				} else if (
					$instr->instructionType >= self::INSTR_SER &&
					$instr->instructionType <= self::INSTR_ROL
				) {
					// Rd|Rr|Dd .... ...d dddd ....
					if ($instr->instructionType === self::INSTR_SER && $instr->arg1 < self::REGISTER_R16) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' only supports r16..r31 register");
					}
					$opCode |= $instr->arg1 << 4;
					if ($instr->instructionType >= self::INSTR_TST) { // TST..ROL
						$opCode |=
							(($instr->arg1 & 0x10) << 5) |
							($instr->arg1 & 0x0F);
					}

				} else if (
					$instr->instructionType === self::INSTR_BREQ ||
					$instr->instructionType === self::INSTR_RCALL
				) {
					$offsetAddress = $labels[$instr->text] - ($address >> 1) - 1;
					if ($instr->instructionType <= self::INSTR_BRID) {
						// BREQ..BRID
						// f        .... ..ff ffff f...
						if ($offsetAddress < -64 || $offsetAddress > 63) {
							throw new CompilerRuntimeException("Instruction '{$instruction}' branch out of range -64..63");
						}
						$opCode |= ($offsetAddress & 0x7F) << 3;
					} else {
						// RJMP..RCALL
						// F        .... FFFF FFFF FFFF
						if ($offsetAddress < -2048 || $offsetAddress > 2047) { // && deviceFlashSize != 4096 todo
							throw new CompilerRuntimeException("Instruction '{$instruction}' relative address out of range -2048..2047");
						}
						$opCode |= $offsetAddress & 0x0FFF;
					}

				} else if (
					$instr->instructionType === self::INSTR_JMP ||
					$instr->instructionType === self::INSTR_CALL
				) {
					// h        .... ...h hhhh ...h + 16h
					$labelAddress = $labels[$instr->text];
					if ($labelAddress < 0 || $labelAddress > 0x3FFFFF) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' address out of range 0..4194303");
					}
					$opCode |=
						(($labelAddress >> 13) & 0x1F0) |
						(($labelAddress >> 16) & 0x001);
					$opCode2 = $labelAddress & 0xFFFF;
					$isLong = true;

				} else if (
					$instr->instructionType === self::INSTR_BRBS ||
					$instr->instructionType === self::INSTR_BRBC
				) {
					// s, f     .... ..ff ffff fsss
					if ($instr->arg2 < -64 || $instr->arg2 > 63) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' branch out of range -64..63");
					}
					$opCode |= (($instr->arg2 & 0x7F) << 3) | $instr->arg1;

				} else if (
					$instr->instructionType >= self::INSTR_ADD &&
					$instr->instructionType <= self::INSTR_MUL
				) {
					// Rd, Rr   .... ..rd dddd rrrr
					$opCode |=
						(($instr->arg1 & 0x10) << 5) |
						($instr->arg1 & 0x0F) |
						($instr->arg2 << 4);
				
				} else if ($instr->instructionType === self::INSTR_MOVW) {
					// Vd, Vr   .... .... dddd rrrr
					if ($instr->arg1 & 0x1) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' first argument must be an even numbered register");
					}
					if ($instr->arg2 & 0x1) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' second argument must be an even numbered register");
					}
					$opCode |=
						(($instr->arg1 >> 1) << 4) |
						($instr->arg2 >> 1);
				
				} else if ($instr->instructionType === self::INSTR_MULS) {
					// Dd, Dr   .... .... dddd rrrr
					if ($instr->arg1 < self::REGISTER_R16) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' first argument only supports r16..r31 register");
					}
					if ($instr->arg2 < self::REGISTER_R16) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' second argument only supports r16..r31 register");
					}
					$opCode |=
						(($instr->arg1 & 0x0F) << 4) |
						($instr->arg2 & 0x0F);
					
				} else if (
					$instr->instructionType >= self::INSTR_MULSU &&
					$instr->instructionType <= self::INSTR_FMULSU
				) {
					// Ad, Ar   .... .... .ddd .rrr
					if ($instr->arg1 < self::REGISTER_R16 || $instr->arg1 > self::REGISTER_R23) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' first argument only supports r16..r23 register");
					}
					if ($instr->arg2 < self::REGISTER_R16 || $instr->arg2 > self::REGISTER_R23) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' second argument only supports r16..r23 register");
					}
					$opCode |=
						(($instr->arg1 & 0x07) << 4) |
						($instr->arg2 & 0x07);
				
				} else if (
					$instr->instructionType === self::INSTR_ADIW ||
					$instr->instructionType === self::INSTR_SBIW
				) {
					// w, K     .... .... KKww KKKK
					if (
						$instr->arg1 !== 24 && $instr->arg1 !== 26 &&
						$instr->arg1 !== 28 && $instr->arg1 !== 30
					) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' first argument only supports r24, r26, r28 or r30 register");
					}
					if ($instr->arg2 < 0 || $instr->arg2 > 63) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' second argument out of range 0..63");
					}
					$opCode |=
						((($instr->arg1 - 24) >> 1) << 4) |
						(($instr->arg2 & 0x30) << 2) |
						($instr->arg2 & 0x0F);

				} else if (
					$instr->instructionType >= self::INSTR_SUBI &&
					$instr->instructionType <= self::INSTR_CBR
				) {
					// Dd, M    .... [MMMM dddd] MMMM
					// Dd, n    .... [nnnn dddd] nnnn (n = ~M, CBR)
					//          _     X    X     _
					if ($instr->arg1 < self::REGISTER_R16) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' first argument only supports r16..r31 register");
					}
					if ($instr->arg2 < -128 || $instr->arg2 > 255) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' second argument out of range -128..255");
					}
					$arg2 = ($instr->instructionType === self::INSTR_CBR) ? ~$instr->arg2 : $instr->arg2;
					$_XX_ = ($arg2 & 0xF0) | ($instr->arg1 & 0x0F);
					$opCode |= ($_XX_ << 4) | ($arg2 & 0x0F);
					
				} else if (
					$instr->instructionType >= self::INSTR_SBRC &&
					$instr->instructionType <= self::INSTR_BLD
				) {
					// Rr, s    .... ...r rrrr .sss
					$opCode |= ($instr->arg1 << 4) | $instr->arg2;

				} else if ($instr->instructionType === self::INSTR_IN) {
					// Rd, P    .... .PPd dddd PPPP
					if ($instr->arg2 < 0 || $instr->arg2 > 63) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' second argument i/o out of range 0..63");
					}
					$opCode |=
						(($instr->arg2 & 0x30) << 5) | ($instr->arg2 & 0x0F) |
						($instr->arg1 << 4);

				} else if ($instr->instructionType === self::INSTR_OUT) {
					// P, Rr    .... .PPr rrrr PPPP
					if ($instr->arg1 < 0 || $instr->arg1 > 63) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' first argument i/o out of range 0..63");
					}
					$opCode |=
						(($instr->arg1 & 0x30) << 5) | ($instr->arg1 & 0x0F) |
						($instr->arg2 << 4);

				} else if (
					$instr->instructionType >= self::INSTR_SBIC &&
					$instr->instructionType <= self::INSTR_CBI
				) {
					// p, s     .... .... pppp psss
					if ($instr->arg1 < 0 || $instr->arg1 > 31) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' first argument i/o out of range 0..31");
					}
					$opCode |= ($instr->arg1 << 3) | $instr->arg2;

				} else if ($instr->instructionType === self::INSTR_LDS) {
					// Rd, i    .... ...d dddd .... + 16i
					if ($instr->arg2 < 0 || $instr->arg2 > 0xFFFF) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' second argument SRAM out of range 0..65535");
					}
					$opCode |= $instr->arg1 << 4;
					$opCode2 = $instr->arg2;
					$isLong = true;

				} else if ($instr->instructionType === self::INSTR_STS) {
					// i, Rd    .... ...d dddd .... + 16i
					if ($instr->arg1 < 0 || $instr->arg1 > 0xFFFF) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' first argument SRAM out of range 0..65535");
					}
					$opCode |= $instr->arg2 << 4;
					$opCode2 = $instr->arg1;
					$isLong = true;

				} else if ($instr->instructionType === self::INSTR_LD) {
					// Rd, e    ...! ...d dddd eeee
					// X: e=11-+, Y: e=10-+, Z: e=00-+
					if ($instr->arg2 !== self::REG_Y && $instr->arg2 !== self::REG_Z) {
						$opCode |= 0x1000;
					}
					$opCode |= ($instr->arg1 << 4) | $instr->arg2;

				} else if ($instr->instructionType === self::INSTR_ST) {
					// e, Rd    ...! ...d dddd eeee
					// X: e=11-+, Y: e=10-+, Z: e=00-+
					if ($instr->arg1 !== self::REG_Y && $instr->arg1 !== self::REG_Z) {
						$opCode |= 0x1000;
					}
					$opCode |= ($instr->arg2 << 4) | $instr->arg1;

				} else if (
					$instr->instructionType === self::INSTR_LDD_Y ||
					$instr->instructionType === self::INSTR_LDD_Z
				) {
					// Rd, [YZ]+q  ..q. qq.d dddd .qqq
					if ($instr->arg2 < 0 || $instr->arg2 > 63) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' second argument displacement out of range 0..63");
					}
					$opCode |=
						(($instr->arg2 & 0x20) << 8) |
						(($instr->arg2 & 0x18) << 7) |
						($instr->arg2 & 0x07) |
						($instr->arg1 << 4);

				} else if (
					$instr->instructionType === self::INSTR_STD_Y ||
					$instr->instructionType === self::INSTR_STD_Z
				) {
					// [YZ]+q, Rd  ..q. qq.d dddd .qqq
					if ($instr->arg1 < 0 || $instr->arg1 > 63) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' first argument displacement out of range 0..63");
					}
					$opCode |=
						(($instr->arg1 & 0x20) << 8) |
						(($instr->arg1 & 0x18) << 7) |
						($instr->arg1 & 0x07) |
						($instr->arg2 << 4);

				} else {
					throw new CompilerRuntimeException("Instruction '{$instruction}' is not implemented");
				}
			} else { // EMIT_LABEL, EMIT_COMMENT
				continue;
			}

			do {
				$buffer .= $this->uint32ToHex($opCode, true);
				$crc += ($opCode >> 8) + $opCode & 0xFF;
				$length += 2;

				if ($length >= 16) {
					$crc = ($crc + $length) & 0xFF;
					echo (
						':' . $this->uint16ToHex($length) . $this->uint32ToHex($address) .
						'00' . $buffer . $this->uint16ToHex(256 - $crc) . "\n"
					);
					$address += $length;
					$length = 0;
					$buffer = '';
					$crc = ($address >> 8) + $address & 0xFF;
				}

				if ($isLong) {
					$opCode = $opCode2;
					$isLong = false;
					continue;
				}
				break;
			} while (true);
		}
		if ($length > 0) {
			$crc = ($crc + $length) & 0xFF;
			echo (
				':' . $this->uint16ToHex($length) . $this->uint32ToHex($address) .
				'00' . $buffer . $this->uint16ToHex(256 - $crc) . "\n"
			);
		}
		echo ":00000001FF\n"; // end segment
	}

	/* $lines = [
		['jmp', 0x0002], // 0x940C, 0x0002
		['sbi', $DDRB, 5], // 0x9A25
		['sbi', $PORTB, 5], // 0x9A2D
		['rcall', 0x0004], // 0xD004
		['cbi', $PORTB, 5], // 0x982D
		['rcall', 0x0002], // 0xD002
		['rjmp', 0x1000 -5], // 0xCFFB
		['ret'], // 0x9508

		['ldi', $r18, 41], // 0xE229
		['ldi', $r19, 150], // 0xE936
		['ldi', $r20, 128], // 0xE840
		['dec', $r20], // 0x954A
		['brne', 0x80 -2], // 0xF7F1
		['dec', $r19], // 0x953A
		['brne', 0x80 -4], // 0xF7E1
		['dec', $r18], // 0x952A
		['brne', 0x80 -6], // 0xF7D1
		['ret'], // 0x9508
	]; */


	protected function getFreeRegister(): int {
		for ($index = self::REGISTER_R16; $index <= self::REGISTER_R31 + 16; $index++) {
			$regIndex = $index % 32;
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
			// eor reg, reg
			$this->emit(self::INSTR_EOR, $allocRes, $allocRes);
		} else if ($alloc->type === self::ALLOCATION_IMMEDIATE) {
			$this->emit(self::INSTR_LDI, $allocRes, $alloc);
		} else if ($alloc->type === self::ALLOCATION_REGISTER) {
			$this->emit(self::INSTR_MOV, $allocRes, $alloc);
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

		$this->emitLabel('start');

		foreach ($node->statements as $statement) {
			$this->generateStmt($statement);
		}

		if ($this->outputHex) {
			$this->emitInstructionsHex();
			return;
		}

		// echo ".equ PORTB, 0x05\n";
		// echo ".equ DDRB, 0x04\n";
		// echo "\n";

		echo ".text\n"; // ?
		echo ".org 0x0000\n"; // ?
		echo "\n";

		$this->emitInstructions();
		echo "\n";

		echo ".end\n"; // ?
	}

}