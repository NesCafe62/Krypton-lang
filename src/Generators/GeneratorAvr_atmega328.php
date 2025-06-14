<?php

/* Special Registers:
SREG - Status Register (0x3F)
SP - Stack Pointer (0x3D, 0x3E)
EIND - Extended Indirect Register (0x3C)
RAMPZ - RAM Pointer Register Z (0x3B)
SPMCSR - Store Program Memory Control and Status Register (0x37)
MCUCR - MCU Control Register (0x35)
MCUSR - MCU Status Register (0x34)
OSCCAL - Oscillator Calibration Register (0x66)
CLKPR - Clock Prescale Register (0x61)
WDTCSR - Watchdog Timer Control and Status Register (0x60)
EECR - EEPROM Control Register (0x1F)
EEDR - EEPROM Data Register (0x1E)
EEAR - EEPROM Address Register (0x1D, 0x1C)
GTCCR - General Timer/Counter Control Register (0x43)
TCCR0A - Timer/Counter0 Control Register A (0x44)
TCCR0B - Timer/Counter0 Control Register B (0x45)
TCNT0 - Timer/Counter0 (0x46)
OCR0A - Output Compare Register 0 A (0x47)
OCR0B - Output Compare Register 0 B (0x48)
TIFR0 - Timer/Counter0 Interrupt Flag Register (0x35)
TIMSK0 - Timer/Counter0 Interrupt Mask Register (0x6E)
TCCR1A - Timer/Counter1 Control Register A (0x80)
TCCR1B - Timer/Counter1 Control Register B (0x81)
TCCR1C - Timer/Counter1 Control Register C (0x82)
TCNT1 - Timer/Counter1 (0x84, 0x85)
ICR1 - Input Capture Register 1 (0x86, 0x87)
OCR1A - Output Compare Register 1 A (0x88, 0x89)
OCR1B - Output Compare Register 1 B (0x8A, 0x8B)
TIFR1 - Timer/Counter1 Interrupt Flag Register (0x36)
TIMSK1 - Timer/Counter1 Interrupt Mask Register (0x6F)
TCCR2A - Timer/Counter2 Control Register A (0xB0)
TCCR2B - Timer/Counter2 Control Register B (0xB1)
TCNT2 - Timer/Counter2 (0xB2)
OCR2A - Output Compare Register 2 A (0xB3)
OCR2B - Output Compare Register 2 B (0xB4)
ASSR - Asynchronous Status Register (0xB6)
TIFR2 - Timer/Counter2 Interrupt Flag Register (0x37)
TIMSK2 - Timer/Counter2 Interrupt Mask Register (0x70)
ADMUX - ADC Multiplexer Selection Register (0x7C)
ADCSRA - ADC Control and Status Register A (0x7A)
ADCSRB - ADC Control and Status Register B (0x7B)
ADCH - ADC Data Register High (0x79)
ADCL - ADC Data Register Low (0x78)
DIDR0 - Digital Input Disable Register 0 (0x7E)
DIDR1 - Digital Input Disable Register 1 (0x7F)
TCCR3A - Timer/Counter3 Control Register A (0x90)
TCCR3B - Timer/Counter3 Control Register B (0x91)
TCCR3C - Timer/Counter3 Control Register C (0x92)
TCNT3 - Timer/Counter3 (0x94, 0x95)
ICR3 - Input Capture Register 3 (0x96, 0x97)
OCR3A - Output Compare Register 3 A (0x98, 0x99)
OCR3B - Output Compare Register 3 B (0x9A, 0x9B)
OCR3C - Output Compare Register 3 C (0x9C, 0x9D)
TIFR3 - Timer/Counter3 Interrupt Flag Register (0x38)
TIMSK3 - Timer/Counter3 Interrupt Mask Register (0x71)
TCCR4A - Timer/Counter4 Control Register A (0xA0)
TCCR4B - Timer/Counter4 Control Register B (0xA1)
TCCR4C - Timer/Counter4 Control Register C (0xA2)
TCNT4 - Timer/Counter4 (0xA4, 0xA5)
ICR4 - Input Capture Register 4 (0xA6, 0xA7)
OCR4A - Output Compare Register 4 A (0xA8, 0xA9)
OCR4B - Output Compare Register 4 B (0xAA, 0xAB)
OCR4C - Output Compare Register 4 C (0xAC, 0xAD)
TIFR4 - Timer/Counter4 Interrupt Flag Register (0x39)
TIMSK4 - Timer/Counter4 Interrupt Mask Register (0x72)
TCCR5A - Timer/Counter5 Control Register A (0x120)
TCCR5B - Timer/Counter5 Control Register B (0x121)
TCCR5C - Timer/Counter5 Control Register C (0x122)
TCNT5 - Timer/Counter5 (0x124, 0x125)
ICR5 - Input Capture Register 5 (0x126, 0x127)
OCR5A - Output Compare Register 5 A (0x128, 0x129)
OCR5B - Output Compare Register 5 B (0x12A, 0x12B)
OCR5C - Output Compare Register 5 C (0x12C, 0x12D)
TIFR5 - Timer/Counter5 Interrupt Flag Register (0x3A)
TIMSK5 - Timer/Counter5 Interrupt Mask Register (0x73)

I/O Registers:
PINB - Port B Input Pins (0x23)
DDRB - Port B Data Direction Register (0x24)
PORTB - Port B Data Register (0x25)
PINC - Port C Input Pins (0x26)
DDRC - Port C Data Direction Register (0x27)
PORTC - Port C Data Register (0x28)
PIND - Port D Input Pins (0x29)
DDRD - Port D Data Direction Register (0x2A)
PORTD - Port D Data Register (0x2B)
PINE - Port E Input Pins (0x2C)
DDRE - Port E Data Direction Register (0x2D)
PORTE - Port E Data Register (0x2E)
PINF - Port F Input Pins (0x31)
DDRF - Port F Data Direction Register (0x32)
PORTF - Port F Data Register (0x33)

Interrupt Registers:
EICRA - External Interrupt Control Register A (0x69)
EIMSK - External Interrupt Mask Register (0x3D)
EIFR - External Interrupt Flag Register (0x3C)
PCICR - Pin Change Interrupt Control Register (0x68)
PCMSK0 - Pin Change Mask Register 0 (0x6B)
PCMSK1 - Pin Change Mask Register 1 (0x6C)
PCMSK2 - Pin Change Mask Register 2 (0x6D)
PCIFR - Pin Change Interrupt Flag Register (0x3B)

Other Registers:
GPIOR0 - General Purpose I/O Register 0 (0x1E)
GPIOR1 - General Purpose I/O Register 1 (0x2A)
GPIOR2 - General Purpose I/O Register 2 (0x2B)
ACSR - Analog Comparator Control and Status Register (0x50)
DWDR - debugWIRE Data Register (0x51)
SMCR - Sleep Mode Control Register (0x53)
MCUCR - MCU Control Register (0x55)
MCUSR - MCU Status Register (0x54)
SPCR - SPI Control Register (0x4C)
SPSR - SPI Status Register (0x4D)
SPDR - SPI Data Register (0x4E)
UCSR0A - USART0 Control and Status Register A (0xC0)
UCSR0B - USART0 Control and Status Register B (0xC1)
UCSR0C - USART0 Control and Status Register C (0xC2)
UBRR0 - USART0 Baud Rate Register (0xC4, 0xC5)
UDR0 - USART0 I/O Data Register (0xC6)
*/

// .equ RAMEND	= 0x08ff
// .equ SPL		= 0x3d
// .equ SPH		= 0x3e

// https://github.com/CalebJ2/avr-assembler/blob/master/m328Pdef.inc

// 0x0000 - 0x001F: General Purpose Register File (32 x 8-bit) (r0..r31)
// 0x0020 - 0x005F: I/O Memory (64 x 8-bit)
// 0x0060 - 0x00FF: Extended I/O Memory Registers (160 x 8-bit)
// 0x0100 - 0x08FF: Internal SRAM (2048 x 8-bit)

// in compiler explorer use "AVR gcc x.x.x" with `-mmcu=atmega328` argument

class GeneratorAvr_atmega328 implements GeneratorInterface {

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

	/* * @var int */
	// protected $prevSwappableIndex;

	protected const EMIT_INSTRUCTION = 1; // instr [left], [right]
	protected const EMIT_INSTRUCTION_LDD_STD = 2; // instr reg, [YZ]+offset | instr [YZ]+offset, reg
	protected const EMIT_INSTRUCTION_JUMP_CALL = 3; // instr label
	protected const EMIT_LABEL = 4;
	protected const EMIT_COMMENT = 5;

	protected const ROOT_SCOPE_NESTING = 1;

	// registers
	protected const REGISTER_NONE = 32;
	protected const REGISTER_R0 = 0;
	protected const REGISTER_R1 = 1;
	protected const REGISTER_R16 = 16;
	protected const REGISTER_R23 = 23;
	protected const REGISTER_R25 = 25;
	protected const REGISTER_R28 = 28;
	protected const REGISTER_R29 = 29;
	protected const REGISTER_R31 = 31;
	// R0, R1 reserved as mul result (in compiler explorer R0 used as temp, R1 used as zero)
	// R26, R27 - X address
	// R28, R29 reserved as Y address (stack base)
	// R30, R31 reserved as Z address

	// instruction argument types
	protected const ARG_NONE = 0;
	protected const ARG_IMMEDIATE = 1;
	protected const ARG_REGISTER = 2;
	protected const ARG_ADDRESS = 3;
	protected const ARG_ADDRESS_OFFSET = 4;
	protected const ARG_BRANCH_OFFSET = 5;
	// protected const ARG_STACK_8 = 6;
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
	
	protected const CMP_EQ = 1;
	protected const CMP_NOT_EQ = 2;
	protected const CMP_L_EQ = 3;
	protected const CMP_G_EQ = 4;
	protected const CMP_L = 5;
	protected const CMP_G = 6;
	
	protected const SIZE_8 = 1;
	// protected const SIZE_16 = 2;
	// protected const SIZE_24 = 3;
	// protected const SIZE_32 = 4;
	// protected const SIZE_64 = 8;
	

	/** @var bool */
	protected $outputHex;

	public function __construct(bool $outputHex = false) {
		$this->outputHex = $outputHex;
	}

	protected function init(): void {
		$this->stackPos = 0;
		$this->scopeNesting = self::ROOT_SCOPE_NESTING;
		$this->identifiers = [];
		$this->identifiersStack = [];
		$this->allocated = [];
		for ($i = self::REGISTER_R0; $i <= self::REGISTER_R31; $i++) {
			$this->allocated[$i] = null;
		}
		$this->instructions = [];
		/* $this->prevSwappableIndex = 0; */

		$this->labelIndex = 0;
	}
	
	protected const INSTR_NONE		= 0;

	protected const INSTR_NOP		= 1;
	protected const INSTR_SEC		= 2;
	protected const INSTR_CLC		= 3;
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
	protected const INSTR_CP		= 79;
	protected const INSTR_CPC		= 80;
	protected const INSTR_CPSE		= 81;
	protected const INSTR_MOV		= 82;
	protected const INSTR_MUL		= 83;
	protected const INSTR_MOVW		= 84;
	protected const INSTR_MULS		= 85;
	protected const INSTR_MULSU		= 86;
	protected const INSTR_FMUL		= 87;
	protected const INSTR_FMULS		= 88;
	protected const INSTR_FMULSU	= 89;
	protected const INSTR_ADIW		= 90;
	protected const INSTR_SBIW		= 91;
	protected const INSTR_SUBI		= 92;
	protected const INSTR_SBCI		= 93;
	protected const INSTR_ANDI		= 94;
	protected const INSTR_ORI		= 95;
	protected const INSTR_SBR		= 96;
	protected const INSTR_CPI		= 97;
	protected const INSTR_LDI		= 98;
	protected const INSTR_CBR		= 99;
	protected const INSTR_SBRC		= 100;
	protected const INSTR_SBRS		= 101;
	protected const INSTR_BST		= 102;
	protected const INSTR_BLD		= 103;
	protected const INSTR_IN		= 104;
	protected const INSTR_OUT		= 105;
	protected const INSTR_SBIC		= 106;
	protected const INSTR_SBIS		= 107;
	protected const INSTR_SBI		= 108;
	protected const INSTR_CBI		= 109;
	protected const INSTR_LDS		= 110;
	protected const INSTR_STS		= 111;
	protected const INSTR_LD		= 112;
	protected const INSTR_ST		= 113;
	protected const INSTR_LDD_Y		= 114;
	protected const INSTR_LDD_Z		= 115;
	protected const INSTR_STD_Y		= 116;
	protected const INSTR_STD_Z		= 117;

	// https://github.com/Ro5bert/avra/blob/afdbda414ba3c062b528d74a3f2139e2418b04ff/src/mnemonic.c#L39
	// https://sourceware.org/binutils/docs/as/AVR-Opcodes.html

	// (*r - source, *d - destination)
	// Rr, Rd   any register
	// Dr, Dd   `ldi`, `muls` register (r16..r31)
	// Vr, Vd   `movw` even register (r0, r2, ..., r28, r30)
	// Ar, Ad   `fmul*`, `mulsu` register (r16..r23)
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
		self::INSTR_NOP			=> [0x0000, 'nop'],		//          0000 0000 0000 0000
		self::INSTR_SEC			=> [0x9408, 'sec'],		//          1001 0100 0000 1000
		self::INSTR_CLC			=> [0x9488, 'clc'],		//          1001 0100 1000 1000
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
		self::INSTR_BSET		=> [0x9408, 'bset'],	// s        1001 0100 0sss 1000
		self::INSTR_BCLR		=> [0x9488, 'bclr'],	// s        1001 0100 1sss 1000
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
		self::INSTR_CP			=> [0x1400, 'cp'],		// Rd, Rr   0001 01rd dddd rrrr
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
		self::INSTR_STD_Y		=> [0x8208, 'std'],		// Y+q, Rd  10q0 qq1d dddd 1qqq    (DF_TINY1X)  (b, Rr?)
		self::INSTR_STD_Z		=> [0x8200, 'std'],		// Z+q, Rd  10q0 qq1d dddd 0qqq    (DF_TINY1X)  (b, Rr?)

		// 'count'				=> [0], // (0)

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
		throw new CompilerRuntimeException("Invalid emit argument alloc: {$alloc->type}");
	}
	
	/**
	 * @param int $instructionType
	 * @param Allocation|object|null $arg1
	 * @param Allocation|object|null $arg2
	 * @param Allocation|object|null $arg3
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
	
	/**
	 * @param Allocation|object $regAlloc
	 * @param int $address
	 */
	protected function emitIn(object $regAlloc, int $address): void {
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION,
			'instructionType' => self::INSTR_IN,
			'arg1' => $regAlloc->value, 'arg1Type' => self::ARG_REGISTER,
			'arg2' => $address, 'arg2Type' => self::ARG_ADDRESS,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => '',
			'nArgs' => 2,
		];
	}
	
	/**
	 * @param int $address
	 * @param Allocation|object $regAlloc
	 */
	protected function emitOut(int $address, object $regAlloc): void {
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION,
			'instructionType' => self::INSTR_OUT,
			'arg1' => $address, 'arg1Type' => self::ARG_ADDRESS,
			'arg2' => $regAlloc->value, 'arg2Type' => self::ARG_REGISTER,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => '',
			'nArgs' => 2,
		];
	}
	
	/**
	 * supports branch instructions to label
	 * @param int $instructionType
	 * @param string $label
	 */
	protected function emitJump(int $instructionType, string $label): void {
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION_JUMP_CALL,
			'instructionType' => $instructionType,
			'arg1' => 0, 'arg1Type' => self::ARG_NONE,
			'arg2' => 0, 'arg2Type' => self::ARG_NONE,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => $label,
			'nArgs' => 0,
		];
	}
	
	/**
	 * only uses offset address, if need branch to label use emitJump
	 * @param int $instructionType
	 * @param int $offset
	 */
	protected function emitBranch(int $instructionType, int $offset): void {
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION,
			'instructionType' => $instructionType,
			'arg1' => $offset, 'arg1Type' => self::ARG_BRANCH_OFFSET,
			'arg2' => 0, 'arg2Type' => self::ARG_NONE,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => '',
			'nArgs' => 1,
		];
	}

	protected function emitCall(int $instructionType, string $name): void {
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION_JUMP_CALL,
			'instructionType' => $instructionType,
			'arg1' => 0, 'arg1Type' => self::ARG_NONE,
			'arg2' => 0, 'arg2Type' => self::ARG_NONE,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => $name,
			'nArgs' => 0,
		];
	}
	
	/**
	 * @param Allocation|object $regAlloc
	 * @param int $pointerReg
	 * @param Allocation|object $allocStack
	 */
	protected function emitLDD(object $regAlloc, int $pointerReg, object $allocStack): void {
		if ($pointerReg !== self::REG_Y && $pointerReg !== self::REG_Z) {
			throw new CompilerRuntimeException("LDD pointer register need to be `REG_Y` or `REG_Z`");
		}
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION_LDD_STD,
			'instructionType' => ($pointerReg === self::REG_Y) ? self::INSTR_LDD_Y : self::INSTR_LDD_Z,
			'arg1' => $regAlloc->value, 'arg1Type' => self::ARG_REGISTER,
			'arg2' => $allocStack->value, 'arg2Type' => self::ARG_ADDRESS_OFFSET,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => '',
			'nArgs' => 2,
		];
	}

	/**
	 * @param int $pointerReg
	 * @param Allocation|object $allocStack
	 * @param Allocation|object $regAlloc
	 */
	protected function emitSTD(int $pointerReg, object $allocStack, object $regAlloc): void {
		if ($pointerReg !== self::REG_Y && $pointerReg !== self::REG_Z) {
			throw new CompilerRuntimeException("STD pointer register need to be `REG_Y` or `REG_Z`");
		}
		$this->instructions[] = (object) [
			'type' => self::EMIT_INSTRUCTION_LDD_STD,
			'instructionType' => ($pointerReg === self::REG_Y) ? self::INSTR_STD_Y : self::INSTR_STD_Z,
			'arg1' => $allocStack->value, 'arg1Type' => self::ARG_ADDRESS_OFFSET,
			'arg2' => $regAlloc->value, 'arg2Type' => self::ARG_REGISTER,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => '',
			'nArgs' => 2,
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
		if ($argType === self::ARG_BRANCH_OFFSET) {
			if ($arg < 0) {
				return '.' . (string) $arg; // .-2
			} else {
				return '.+' . (string) $arg; // .+2
			}
			// or use `1b`, `2f`
		}
		/* if ($argType === self::ARG_STACK_64) {
			return "QWORD [rbp-{$arg}]";
		}
		if ($argType === self::ARG_STACK_32) {
			return "DWORD [rbp-{$arg}]";
		}
		if ($argType === self::ARG_STACK_16) {
			return "WORD [rbp-{$arg}]";
		} */
		/* if ($argType === self::ARG_STACK_8) {
			return (string) $arg;
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
			} else if ($instr->type === self::EMIT_INSTRUCTION_JUMP_CALL) {
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
			if ($instr->type <= self::EMIT_INSTRUCTION_JUMP_CALL) { // EMIT_INSTRUCTION, EMIT_INSTRUCTION_LDD_STD, EMIT_INSTRUCTION_JUMP_CALL
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
			if ($instr->type <= self::EMIT_INSTRUCTION_JUMP_CALL) { // EMIT_INSTRUCTION, EMIT_INSTRUCTION_LDD_STD, EMIT_INSTRUCTION_JUMP_CALL
				$isLong = false;
				if (!isset(self::$instructionsData[$instr->instructionType])) {
					throw new CompilerRuntimeException("Unknown instruction type '{$instr->instructionType}'");
				}
				[$opCode, $instruction] = self::$instructionsData[$instr->instructionType];
				$opCode2 = 0;
				if ($instr->instructionType <= self::INSTR_BREAK) {
					// zero args, $opCode is already complete

				} else if ($instr->instructionType <= self::INSTR_ELPM) { // LPM, ELPM
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

				} else if ($instr->instructionType <= self::INSTR_BCLR) { // BSET, BCLR
					// s        .... .... .sss ....
					if ($instr->arg1 < 0 || $instr->arg1 > 7) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' argument is out of range 0..7");
					}
					$opCode |= $instr->arg1 << 4;

				} else if ($instr->instructionType <= self::INSTR_ROL) { // SER..ROL
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

				} else if ($instr->instructionType <= self::INSTR_RCALL) { // BREQ..RCALL
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

				} else if ($instr->instructionType <= self::INSTR_CALL) { // JMP, CALL
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

				} else if ($instr->instructionType <= self::INSTR_BRBC) { // BRBS, BRBC
					// s, f     .... ..ff ffff fsss
					if ($instr->arg2 < -64 || $instr->arg2 > 63) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' branch out of range -64..63");
					}
					$opCode |= (($instr->arg2 & 0x7F) << 3) | $instr->arg1;

				} else if ($instr->instructionType <= self::INSTR_MUL) { // ADD..MUL
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
					
				} else if ($instr->instructionType <= self::INSTR_FMULSU) { // MULSU..FMULSU
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
				
				} else if ($instr->instructionType === self::INSTR_SBIW) { // ADIW, SBIW
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

				} else if ($instr->instructionType <= self::INSTR_CBR) { // SUBI..CBR
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
					
				} else if ($instr->instructionType <= self::INSTR_BLD) { // SBRC..BLD
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

				} else if ($instr->instructionType <= self::INSTR_CBI) { // SBIC..CBI
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

				} else if ($instr->instructionType <= self::INSTR_LDD_Z) { // LDD_Y, LDD_Z
					// Rd, [YZ]+q  ..q. qq.d dddd .qqq
					if ($instr->arg2 < 0 || $instr->arg2 > 63) {
						throw new CompilerRuntimeException("Instruction '{$instruction}' second argument displacement out of range 0..63");
					}
					$opCode |=
						(($instr->arg2 & 0x20) << 8) |
						(($instr->arg2 & 0x18) << 7) |
						($instr->arg2 & 0x07) |
						($instr->arg1 << 4);

				} else if ($instr->instructionType <= self::INSTR_STD_Z) { // STD_Y, STD_Z
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
				$address += 2;

				if ($length >= 16) {
					$crc = ($crc + $length) & 0xFF;
					echo (
						':' . $this->uint16ToHex($length) . $this->uint32ToHex($address) .
						'00' . $buffer . $this->uint16ToHex(256 - $crc) . "\n"
					);
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

	protected function newLabel(): string {
		$this->labelIndex++;
		return ".L{$this->labelIndex}";
	}

	protected function getFreeRegister(bool $isR16): int {
		// R16:  16..25
		// !R16: 16..25,0..15
		$maxRegister = self::REGISTER_R25 + ($isR16 ? 0 : 16);
		for ($index = self::REGISTER_R16; $index <= $maxRegister; $index++) {
			$regIndex = $index % 26;
			if ($regIndex <= self::REGISTER_R1 || $regIndex >= self::REGISTER_R28) {
				// reserved registers
				continue;
			}
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
	 * @param bool $isR16
	 * @return Allocation
	 */
	protected function allocateFreeRegister(bool $isR16): object { // int $regSize
		$regIndex = $this->getFreeRegister($isR16);
		if ($regIndex === self::REGISTER_NONE) {
			throw new CompilerRuntimeException("Not enough registers");
		}
		$alloc = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => $regIndex,
			// 'size' => $regSize,
		];
		$this->allocated[$regIndex] = $alloc;
		return $alloc;
	}

	/**
	 * @param int $regIndex
	 * @return Allocation
	 */
	protected function allocateRegister(int $regIndex): object { // int $regSize
		$alloc = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => $regIndex,
			// 'size' => $regSize,
		];
		$this->allocated[$regIndex] = $alloc;
		return $alloc;
	}
	
	/**
	 * @param Allocation|object $alloc
	 * @param bool $zeroAsClr
	 * @return Allocation
	 */
	protected function allocateFreeRegisterFrom(object $alloc, bool $zeroAsClr = false): object {
		if (
			$zeroAsClr &&
			$alloc->type === self::ALLOCATION_IMMEDIATE &&
			$alloc->value === 0
		) {
			$allocRes = $this->allocateFreeRegister(false); // $alloc->size
			// ldi reg, 0
			//   ->
			// clr reg
			$this->emit(self::INSTR_CLR, $allocRes);
		} else if ($alloc->type === self::ALLOCATION_IMMEDIATE) {
			$allocRes = $this->allocateFreeRegister(true /* register should support LDI */); // $alloc->size
			$this->emit(self::INSTR_LDI, $allocRes, $alloc);
		} else {
			$allocRes = $this->allocateFreeRegister(false); // $alloc->size
			if ($alloc->type === self::ALLOCATION_STACK) {
				$this->emitLDD($allocRes, self::REG_Y, $alloc);
			} else {
				$this->emit(self::INSTR_MOV, $allocRes, $alloc);
			}
		}
		return $allocRes;
	}

	/**
	 * @param Allocation|object $alloc
	 * @return Allocation
	 */
	protected function allocateFreeRegisterWithDeallocationFromRegister(object $alloc): object {
		$allocRes = $this->allocateFreeRegister(false); // $alloc->size
		$this->deallocateRegister($alloc);
		$this->emit(self::INSTR_MOV, $allocRes, $alloc);
		return $allocRes;
	}

	/**
	 * @param Allocation|object $allocSource
	 * @param Allocation|object $allocDest
	 */
	protected function storeAllocationTo(object $allocSource, object $allocDest): void {
		if ($allocDest->type === self::ALLOCATION_STACK) {
			if ($allocSource->type !== self::ALLOCATION_REGISTER) {
				throw new CompilerRuntimeException("Move allocation: source allocation type should be register: {$allocSource->type}");
			}
			// stack <- reg
			$this->emitSTD( self::REG_Y, $allocDest, $allocSource);
		} else if ($allocDest->type === self::ALLOCATION_REGISTER) {
			if ($allocSource->type === self::ALLOCATION_REGISTER) {
				// reg <- reg
				$this->emit(self::INSTR_MOV, $allocDest, $allocSource);
			} else if ($allocSource->type === self::ALLOCATION_IMMEDIATE) {
				// todo: might be non-ldi register
				// reg <- immediate
				$this->emit(self::INSTR_LDI, $allocDest, $allocSource);
			} else {
				throw new CompilerRuntimeException("Move allocation: wrong source allocation type: {$allocSource->type}");
			}
		} else {
			throw new CompilerRuntimeException("Move allocation: wrong destination allocation type: {$allocDest->type}");
		}
	}

	/* protected function generateClearXor(int $regIndex): void { // int $regSize
		$alloc = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => $regIndex,
			// 'size' => $regSize,
		];
		$this->emit(self::INSTR_EOR, $alloc, $alloc);
	} */

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
		$allocRegIndex = $this->getFreeRegister(false);
		if ($allocRegIndex === self::REGISTER_NONE) {
			$allocRes = $this->allocateStack(self::SIZE_8);
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
		$this->emit(self::INSTR_MOV, $allocRes, $allocRight);
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
			// 'size' => $alloc->size,
		];
		$this->emit(self::INSTR_MOV, $allocLeft, $alloc);
	}
	
	/**
	 * @param int $value
	 * @return Allocation
	 */
	protected function createImmediate(int $value): object {
		return (object) [
			'type' => self::ALLOCATION_IMMEDIATE,
			'value' => $value,
			// 'size' => self::SIZE_8,
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

	/* *
	 * @param Allocation|object $allocLeft
	 * @param Allocation|object $allocRight
	 * @return object
	 */
	/* protected function generateMultiply(object $allocLeft, object $allocRight): object {
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

		if ($allocLeft->type !== self::ALLOCATION_REGISTER) {
			// ...
		}


		$this->emit(self::INSTR_MULS, $allocLeft, $allocRight);
		$this->emit(self::INSTR_MOV, $allocLeft, $r0);
		// res => R1:R0
		return $allocLeft;

		/ * if (
			$allocLeft->type === self::ALLOCATION_STACK || // and right is not stack
			$allocLeft->type === self::ALLOCATION_IMMEDIATE // and right is not immediate
		) { * /
		// no need to deallocate $allocLeft because it's not a register
//		$allocRes = $this->allocateFreeRegister(false / * ? * /); // to do: size?, prob max($allocLeft->size, $allocRight->size)
//		$this->emit('imul', $allocRes, $allocLeft, $allocRight);
//		return $allocRes;
		// }
	} */

	/**
	 * @param Allocation|object $allocLeft
	 * @param Allocation|object $allocRight
	 * @param bool $isOperatorReminder
	 * @return object
	 */
	protected function generateDivide(object $allocLeft, object $allocRight, bool $isOperatorReminder): object {
		// todo: implement
		// unsigned uint8/uint8 and signed int8/int8:
		// http://www.rjhcoding.com/avr-asm-8bit-division.php
		
		// binary division example
		// http://www.rjhcoding.com/avr-asm-division.php
		
		// int16/int16:
		// https://www.radiokot.ru/forum/viewtopic.php?t=5308&ysclid=m9gyjtr0we40447761
		
		// udivmodhi4 uint16/uint16:
		// https://stackoverflow.com/questions/55196692/mod-in-avr-assembly-divmodhi4
		
		// int16/int8:
		// https://www.cyberforum.ru/avr/thread1900257.html?ysclid=m9gzdguuy7272245746
		
		
		// signed int8/int8:
		/*
		
		
		r_rem  = r26:r27 - reminder
		r_arg1 = r24:r25 - dividend | reminder
		r_arg2 = r22:r23 - divisor | result
		r_cnt  = r21 - counter
		
		
		r25
		r0, r1, r26, r27
		
		.divmod_i8i8
			sub		r_rem, r_rem	    ; clear remainder and carry
			ldi		r_cnt, 9	        ; init loop counter
			rjmp	.divmod_i8i8_entry  ; jump to entry point
		
		.divmod_i8i8_loop:
			rol		r_rem		        ; shift dividend into remainder
			cp		r_rem, r_arg2	    ; compare remainder & divisor
			brcs	.divmod_i8i8_entry  ; remainder < divisor
			sub		r_rem, r_arg2	    ; restore remainder
		
		.divmod_i8i8_entry:
			rol		r_arg1		        ; shift dividend (with CARRY)
			dec		r_cnt		        ; decrement loop counter
			brne 	.divmod_i8i8_loop
		
			com		r_arg1
										; div/mod results to return registers, as for the div() function
			mov		r_arg2, r_arg1	    ; result
			mov		r_arg1, r_rem		; remainder

			
		.divmod_i8i8_neg1:
			; correct dividend/remainder sign
			;
			ret
		.divmod_i8i8_neg2:
			; correct divisor/result sign
			;
			ret
		.divmod_i8i8_exit:
			ret
		

		
		r14 - sign register
		r15 - remainder
		r16 - dividend (result)
		r17 - divisor
		r25 - loop counter

		.divmod_i8i8:
			mov	 r14, r16		; move dividend to sign register
			eor	 r14, r17		; xor sign with divisor
			sbrc r17, 7			; if MSB of divisor set
			neg	 r17			;     change sign of divisor
			sbrc r16, 7			; if MSB of dividend set
			neg	 r16			;     change sign of dividend
			sub	 r15, r15		; clear remainder and carry
			ldi	 r21, 9			; init loop counter
		.divmod_i8i8_loop:
			rol	 r16			; shift left dividend
			dec	 r21			; decrement counter

			brne .L2			; if done
			sbrc r14, 7			;     if MSB of sign register set
			neg	 r16			;         change sign of result
			ret					;     return

		.L2:
			rol	 r15			; shift dividend into remainder
			sub	 r15, r17		; remainder -= divisor

			brcc .L3					; if result negative
			add	 r15, r17				;     restore remainder
			clc							;     clear carry to be shifted into result
			rjmp .divmod_i8i8_loop		; else
		.L3:
			sec							;     set carry to be shifted into result
			rjmp .divmod_i8i8_loop
		*/
		
		throw new CompilerRuntimeException("Division operator is not implemented");
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
			$this->emit(self::INSTR_CP, $allocLeft, $allocRight);
			return (object) [
				'type' => self::ALLOCATION_CMP_FLAG,
				'value' => 0,
				// 'size' => 0, // no size
			];
		}

		if ($allocLeft->type !== self::ALLOCATION_REGISTER) {
			$allocLeft = $this->allocateFreeRegisterFrom($allocLeft, true);
			// prev $allocLeft doesn't need deallocation because it's not a register
		}

		$this->deallocateIfRegister($allocRight);

		/* $allocLeftU8 = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => $allocLeft->value,
			// 'size' => self::SIZE_8,
		]; */
		// $this->emit(self::INSTR_TST, $allocLeft, $allocRight);

		$allocRes = $this->allocateFreeRegister(true);

		$this->emit(self::INSTR_LDI, $allocRes, $this->createImmediate(1));
		if ($operator === '==') {
			$this->emit(self::INSTR_CP, $allocLeft, $allocRight);
			$this->emitBranch(self::INSTR_BREQ, 1);
		} else if ($operator === '!=') {
			$this->emit(self::INSTR_CP, $allocLeft, $allocRight);
			$this->emitBranch(self::INSTR_BRNE, 1);
		} else if ($operator === '<=') {
			// in intel x64 for unsigned need to use different instruction "setbe", is it different for unsigned in avr too?
			$this->emit(self::INSTR_CP, $allocRight, $allocLeft);
			$this->emitBranch(self::INSTR_BRGE, 1); // BRGE and swap cmp operands
		} else if ($operator === '>=') {
			// in intel x64 for unsigned need to use different instruction "setae", is it different for unsigned in avr too?
			$this->emit(self::INSTR_CP, $allocLeft, $allocRight);
			$this->emitBranch(self::INSTR_BRGE, 1);
		} else if ($operator === '<') {
			$this->emit(self::INSTR_CP, $allocLeft, $allocRight);
			$this->emitBranch(self::INSTR_BRLT, 1);
		} else if ($operator === '>') {
			$this->emit(self::INSTR_CP, $allocRight, $allocLeft);
			$this->emitBranch(self::INSTR_BRLT, 1); // BRLT and swap cmp operands
		}
		
		$this->emit(self::INSTR_CLR, $allocRes);
		return $allocRes; // todo: consider size
	}
	
	/**
	 * @param Allocation|object $allocLeft
	 * @param Allocation|object $allocRight
	 * @return object
	 */
	protected function generateLShift(object $allocLeft, object $allocRight): object {
		// https://aykevl.nl/2021/02/avr-bitshift/
		
		// int16 << 1:
		// lsl r0
		// rol r1
		
		// int16 << 2:
		// lsl r0
		// ror r1
		// lsl r0
		// ror r1
		
		// int16 << 4:
		// swap r1
		// andi r1, 0xF0 // r16..r31
		// swap r0
		// eor r1, r0
		// andi r0, 0xF0 // r16..r31
		// eor r1, r0
		
		// int16 << 6:
		// ; clear temporary register
		// clr __tmp_reg__
		// ; shift by one
		// lsr r1
		// ror r0
		// ror __tmp_reg__
		// ; shift again by one
		// lsr r1
		// ror r0
		// ror __tmp_reg__
		// ; move registers back
		// mov r1, r0
		// mov r0, __tmp_reg__
		
		// int16 << 7:
		// ; clear temporary register before use
		// clr __tmp_reg__
		// ; shift 1 to the right into the temporary register
		// lsr r1
		// ror r0
		// ror __tmp_reg__
		// ; shift left by 8
		// mov r1, r0
		// mov r0, __tmp_reg__
		
		
		// int32 << 1:
		// lsl r0
		// rol r1
		// rol r2
		// rol r3
		
		// int32 << 4:
		// ; shift r3
		// swap r3
		// andi r3, 0xF0 // r16..r31
		// ; shift r2
		// swap r2
		// eor r3, r2
		// andi r2, 0xF0 // r16..r31
		// eor r3, r2
		// ; shift r1
		// swap r1
		// eor r2, r1
		// andi r1, 0xF0 // r16..r31
		// eor r2, r1
		// ; shift r0
		// swap r0
		// eor r1, r0
		// andi r0, 0xF0 // r16..r31
		// eor r1, r0


		// int8 << 1:
		// lsl r0

		// int8 << 4:
		// swap r0
		// andi r0, 0xF0 // r16..r31
		
		// int8 << 5:
		// swap r0
		// lsl r0
		// andi r0, 0xF0 // r16..r31

		// int8 << 6:
		// [ror r0
		// ror r0
		// ror r0
		// andi r0, 0xC0] // r16..r31
		
		// swap r0
		// lsl r0
		// lsl r0
		// andi r0, 0xC0 // r16..r31

		// int8 << 7:
		// [lsr r0
		// clr r0
		// ror r0]
		
		// ror r0
		// clr r0
		// ror r0
		
		// or int8 << 7:
		// bst r0, 0
		// clr r0
		// bld r0, 7
		
		if ($allocRight->type === self::ALLOCATION_IMMEDIATE) {
			// if size === int8
			if ($allocRight->value < 4) {
				for ($i = 0; $i < $allocRight->value; $i++) {
					$this->emit(self::INSTR_LSL, $allocLeft);
				}
			} else if ($allocRight->value === 4) {
				$this->emit(self::INSTR_SWAP, $allocLeft);
				$this->emit(self::INSTR_ANDI, $allocLeft, $this->createImmediate(0xF0));
			} else if ($allocRight->value === 5) {
				$this->emit(self::INSTR_SWAP, $allocLeft);
				$this->emit(self::INSTR_LSL, $allocLeft);
				$this->emit(self::INSTR_ANDI, $allocLeft, $this->createImmediate(0xF0));
			} else if ($allocRight->value === 6) {
				$this->emit(self::INSTR_SWAP, $allocLeft);
				$this->emit(self::INSTR_LSL, $allocLeft);
				$this->emit(self::INSTR_LSL, $allocLeft);
				$this->emit(self::INSTR_ANDI, $allocLeft, $this->createImmediate(0xC0));
			} else if ($allocRight->value === 7) {
				$this->emit(self::INSTR_ROR, $allocLeft);
				$this->emit(self::INSTR_CLR, $allocLeft);
				$this->emit(self::INSTR_ROR, $allocLeft);
			} else { // >= 8
				$this->emit(self::INSTR_CLR, $allocLeft);
				// $this->emit(self::INSTR_LDI, $allocLeft, (object) ['type' => self::ALLOCATION_IMMEDIATE, 'value' => 0]);
			}
		} else {
			// todo: if (N >= 8) then 0 else:
			
			if ($allocRight->type === self::ALLOCATION_STACK) {
				$allocRight = $this->allocateFreeRegisterFrom($allocRight, true);
			}
			$this->deallocateRegister($allocRight);
			
			// $labelBeginLoop = $this->newLabel();
			$regCounter = $this->allocateFreeRegister(false);
			// todo: probably use allocRight as counter directly (but to do it ensure register is not used elsewhere)
			if ($regCounter->value !== $allocRight->value) { // skip in case same register
				$this->emit(self::INSTR_MOV, $regCounter, $allocRight);
			}
			// $this->emitLabel($labelBeginLoop);
			$this->emit(self::INSTR_ASR, $allocLeft);
			$this->emit(self::INSTR_DEC, $regCounter);
			$this->emitBranch(self::INSTR_BRNE, -3);
			// $this->emitJump(self::INSTR_BRNE, $labelBeginLoop);
			$this->deallocateRegister($regCounter);

			// int8 << <R>:
			// mov __tmp_reg__, <R>
			// .L1:
			// lsl r0
			// dec __tmp_reg__
			// brne .L1
			
			// int16 << <R>:
			// mov __tmp_reg__, <R>
			// .L1:
			// lsl r0
			// rol r1
			// dec __tmp_reg__
			// brne .L1
			
			// int32 << <R>:
			// mov __tmp_reg__, <R>
			// .L1:
			// lsl r0
			// rol r1
			// rol r2
			// rol r3
			// dec __tmp_reg__
			// brne .L1
		}
		
		return $allocLeft;

		/* if ($allocRight->type !== self::ALLOCATION_REGISTER) {
			$allocRight = $this->allocateFreeRegisterFrom($allocRight, true);
			// prev $allocRight doesn't need deallocation because it's not a register
		}
		$allocRightU8 = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => $allocRight->value,
			// 'size' => self::SIZE_8,
		];
		$this->emit('shl', $allocLeft, $allocRightU8);
		return $allocLeft; */
	}
	
	/**
	 * @param Allocation|object $allocLeft
	 * @param Allocation|object $allocRight
	 * @return object
	 */
	protected function generateRShift(object $allocLeft, object $allocRight): object {
		// int8 >> 6:
		// bst r0, 6
		// lsl r0
		// sbc r0, r0
		// bld r0, 0

		// int8 >> 7+:
		// lsl r0
		// sbc r0, r0
		
		if ($allocRight->type === self::ALLOCATION_IMMEDIATE) {
			// if size === int8
			if ($allocRight->value < 6) {
				for ($i = 0; $i < $allocRight->value; $i++) {
					$this->emit(self::INSTR_ASR, $allocLeft);
				}
			} else if ($allocRight->value === 6) {
				$this->emit(self::INSTR_BST, $allocLeft, $allocRight);
				$this->emit(self::INSTR_LSL, $allocLeft);
				$this->emit(self::INSTR_SBC, $allocLeft, $allocLeft);
				$this->emit(self::INSTR_BLD, $allocLeft, $this->createImmediate(0));
			} else { // >= 7
				// if >= 0 then 0
				// if < 0 then -1
				$this->emit(self::INSTR_LSL, $allocLeft);
				$this->emit(self::INSTR_SBC, $allocLeft, $allocLeft);
			}
		} else {
			// if (N >= 7) then 0|-1 else:
			
			if ($allocRight->type === self::ALLOCATION_STACK) {
				$allocRight = $this->allocateFreeRegisterFrom($allocRight, true);
			}
			$this->deallocateRegister($allocRight);

			// $labelBeginLoop = $this->newLabel();
			$regCounter = $this->allocateFreeRegister(false);
			// todo: probably use allocRight as counter directly (but to do it ensure register is not used elsewhere)
			if ($regCounter->value !== $allocRight->value) { // skip in case same register
				$this->emit(self::INSTR_MOV, $regCounter, $allocRight);
			}
			// $this->emitLabel($labelBeginLoop);
			$this->emit(self::INSTR_ASR, $allocLeft);
			$this->emit(self::INSTR_DEC, $regCounter);
			$this->emitBranch(self::INSTR_BRNE, -3);
			// $this->emitJump(self::INSTR_BRNE, $labelBeginLoop);
			$this->deallocateRegister($regCounter);

			// int8 >> <R>:
			// mov __tmp_reg__, <R>
			// .L1:
			// asr r0
			// dec __tmp_reg__
			// brne .L1
			
			// int16 >> <R>:
			// mov __tmp_reg__, <R>
			// .L1:
			// asr r0
			// ror r1
			// dec __tmp_reg__
			// brne .L1
			
			// int32 >> <R>:
			// mov __tmp_reg__, <R>
			// .L1:
			// asr r0
			// ror r1
			// ror r2
			// ror r3
			// dec __tmp_reg__
			// brne .L1
		}

		return $allocLeft;
	}
	
	/**
	 * @param NodeExpressionBinary|object $node
	 * @param bool $allocAsCmpFlag
	 * @return Allocation
	 */
	protected function generateExpressionBinary(object $node, bool $allocAsCmpFlag = false): object {
		$allocLeft = $this->generateExpression($node->left, true);
		$allocRight = $this->generateExpression($node->right, true);

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


		if ($node->operator === '<<') {
			return $this->generateLShift($allocLeft, $allocRight);
		}
		if ($node->operator === '>>') {
			return $this->generateRShift($allocLeft, $allocRight);
		}
		
		if ($node->operator === '^') {
			if ($allocRight->type !== self::ALLOCATION_REGISTER) {
				$allocRight = $this->allocateFreeRegisterFrom($allocRight, true);
				// prev $allocRight doesn't need deallocation because it's not a register
			}
			$this->deallocateRegister($allocRight);
			
			$this->emit(self::INSTR_EOR, $allocLeft, $allocRight);
			return $allocLeft;
		}
		
		if ($node->operator === '*') {
			if ($allocRight->type !== self::ALLOCATION_REGISTER) {
				$allocRight = $this->allocateFreeRegisterFrom($allocRight, true);
				// prev $allocRight doesn't need deallocation because it's not a register
			}
			$this->deallocateRegister($allocRight);
			
			$this->emit(self::INSTR_MUL, $allocLeft, $allocRight);
			$R0 = (object) [
				'type' => self::ALLOCATION_REGISTER,
				'value' => self::REGISTER_R0,
				// 'size' => self::SIZE_64,
			];
			$this->emit(self::INSTR_MOV, $allocLeft, $R0); // clr r1?
			return $allocLeft;
		}
		
		$this->deallocateIfRegister($allocRight);

		
		if ($allocRight->type === self::ALLOCATION_STACK) {
			$allocRight = $this->allocateFreeRegisterFrom($allocRight, true);
		}
		
		// todo: maybe zero other bits except the lowest one (cast to bool)
		if ($node->operator === '&&') {
			$instructionType = ($allocRight->type === self::ALLOCATION_IMMEDIATE)
				? self::INSTR_ANDI
				: self::INSTR_AND;
		} else if ($node->operator === '||') {
			$instructionType = ($allocRight->type === self::ALLOCATION_IMMEDIATE)
				? self::INSTR_ORI
				: self::INSTR_OR;
		} else if ($node->operator === '+') {
			if ($allocRight->type === self::ALLOCATION_IMMEDIATE) {
				$allocRight = $this->createImmediate(-$allocRight->value); // SUBI range -128..255
				$instructionType = self::INSTR_SUBI;
			} else {
				$instructionType = self::INSTR_ADD;
			}
		} else if ($node->operator === '-') {
			$instructionType = ($allocRight->type === self::ALLOCATION_IMMEDIATE)
				? self::INSTR_SUBI
				: self::INSTR_SUB;
		} else if ($node->operator === '&') {
			$instructionType = ($allocRight->type === self::ALLOCATION_IMMEDIATE)
				? self::INSTR_ANDI
				: self::INSTR_AND;
		} else if ($node->operator === '|') {
			$instructionType = ($allocRight->type === self::ALLOCATION_IMMEDIATE)
				? self::INSTR_ORI
				: self::INSTR_OR;
		} else {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Operator '{$node->operator}' is not implemented");
		}

		$this->emit($instructionType, $allocLeft, $allocRight);
		return $allocLeft;
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
			$this->emit(self::INSTR_NEG, $alloc);
			return $alloc;
		}
		if ($node->operator === '~') {
			$this->emit(self::INSTR_COM, $alloc);
			return $alloc;
		}
		if ($node->operator === '!') {
			// todo: temporary bitwise not
			$this->emit(self::INSTR_COM, $alloc);
			return $alloc;
		}

		throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Operator '{$node->operator}' is not implemented");
	}

	/**
	 * @param NodeExpressionUpdate|object $node
	 * @param bool $isResultUsed
	 * @return Allocation|null
	 */
	protected function generateExpressionUpdate(object $node, bool $isResultUsed): ?object {
		if ($node->operator === '++') {
			$step = 1;
		} else if ($node->operator === '--') {
			$step = -1;
		} else {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Wrong update operator '{$node->operator}'");
		}

		if ($node->prefix || !$isResultUsed) {
			$allocRes = $this->generateIncrementVariable($node->value, $step);
			if (!$isResultUsed) {
				$this->deallocateRegister($allocRes);
				return null;
			}
			return $allocRes;
		}

		// else: $isResultUsed && !$node->prefix
		$alloc = $this->generateExpressionIdentifier($node->value);
		$allocRes = $this->allocateFreeRegisterFrom($alloc); // $alloc->size
		$tempReg = $this->generateIncrementVariable($node->value, $step, $allocRes);
		$this->deallocateRegister($tempReg);
		return $allocRes;
	}

	/**
	 * @param NodeIdentifier|object $node
	 * @param int $step
	 * @param Allocation|object|null $loadedReg
	 * @return Allocation
	 */
	protected function generateIncrementVariable(object $node, int $step, ?object $loadedReg = null): object {
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
		
		if ($loadedReg !== null) {
			// mov alloc, allocReg (reg <- reg)
			$alloc = $this->allocateFreeRegisterFrom($loadedReg);
		} else {
			// ldd alloc, allocStack (reg <- stack)
			$alloc = $this->allocateFreeRegisterFrom($data->alloc);
		}
		
		if ($step === 1) {
			$this->emitComment("{$identifier} += 1");
			$this->emit(self::INSTR_INC, $alloc);
		} else if ($step === -1) {
			$this->emitComment("{$identifier} -= 1");
			$this->emit(self::INSTR_DEC, $alloc);
		} else {
			throw new CompilerRuntimeException("Increment step '{$step}' should be '1' or '-1'");
		}
		
		$this->storeAllocationTo($alloc, $data->alloc);
		return $alloc;
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
			// reg    <- immediate:  ldi reg immediate
			// reg1   <- reg2:       mov reg1 reg2
			// stack  <- reg:        std stack reg
			// stack  <- immediate:  ldi reg immediate, std stack reg
			// stack1 <- stack2:     ldd reg stack2,    std stack1 reg
			if (
				$allocRight->type !== self::ALLOCATION_REGISTER &&
				$allocLeft->type === self::ALLOCATION_STACK
			) {
				// stack1 = stack2/immediate
				//   ->
				// reg = stack2/immediate
				// stack1 = reg
				$allocRight = $this->allocateFreeRegisterFrom($allocRight);
			}

			$this->storeAllocationTo($allocRight, $allocLeft);

			// there is no logic yet for storing variables in registers
			/* if ($allocLeft->type === self::ALLOCATION_REGISTER) {
				$this->deallocateIfRegister($allocRight);
				return $allocLeft;
			} */
			
			// else: $allocLeft->type === self::ALLOCATION_STACK
			// return register with loaded value (assuming stack value will not change before accessing copied value from this register)
			return $allocRight;
		}

		if (!$data->mutable) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Can't modify an immutable variable '{$identifier}'");
		}
		if ($data->initScope === 0) {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Usage of uninitialized variable '{$identifier}'");
		}

		// $allocLeft = $data->alloc;
		$allocLeft = $this->allocateFreeRegisterFrom($data->alloc);

		if ($allocRight->type === self::ALLOCATION_IMMEDIATE && $allocRight->value === 1) {
			if ($node->operator === '+=') {
				$this->emitComment("{$identifier} += 1");
				$this->emit(self::INSTR_INC, $allocLeft);
				$this->storeAllocationTo($allocLeft, $data->alloc);
				return $allocLeft;
			}
			if ($node->operator === '-=') {
				$this->emitComment("{$identifier} -= 1");
				$this->emit(self::INSTR_DEC, $allocLeft);
				$this->storeAllocationTo($allocLeft, $data->alloc);
				return $allocLeft;
			}
		}

		if ($node->operator === '^=') {
			if ($allocRight->type !== self::ALLOCATION_REGISTER) {
				$allocRight = $this->allocateFreeRegisterFrom($allocRight);
			}
			$this->deallocateRegister($allocRight);
			$this->emitComment("{$identifier} ^= (...)");
			$this->emit(self::INSTR_EOR, $allocLeft, $allocRight);
			$this->storeAllocationTo($allocLeft, $data->alloc);
			return $allocLeft;
		}

		if ($node->operator === '*=') {
			if ($allocRight->type !== self::ALLOCATION_REGISTER) {
				$allocRight = $this->allocateFreeRegisterFrom($allocRight);
			}
			$this->deallocateRegister($allocRight);
			$this->emitComment("{$identifier} *= (...)");

			$this->emit(self::INSTR_MUL, $allocLeft, $allocRight);
			$R0 = (object) [
				'type' => self::ALLOCATION_REGISTER,
				'value' => self::REGISTER_R0,
				// 'size' => self::SIZE_64,
			];
			$this->emit(self::INSTR_MOV, $allocLeft, $R0); // clr r1?
			$this->storeAllocationTo($allocLeft, $data->alloc);
			return $allocLeft;
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
			if ($allocRight->type === self::ALLOCATION_IMMEDIATE) {
				$allocRight = $this->createImmediate(-$allocRight->value); // SUBI range -128..255
				$instructionType = self::INSTR_SUBI;
			} else {
				$instructionType = self::INSTR_ADD;
			}
		} else if ($node->operator === '-=') {
			$instructionType = ($allocRight->type === self::ALLOCATION_IMMEDIATE)
				? self::INSTR_SUBI
				: self::INSTR_SUB;
		} else if ($node->operator === '&=') {
			$instructionType = ($allocRight->type === self::ALLOCATION_IMMEDIATE)
				? self::INSTR_ANDI
				: self::INSTR_AND;
		} else if ($node->operator === '|=') {
			$instructionType = ($allocRight->type === self::ALLOCATION_IMMEDIATE)
				? self::INSTR_ORI
				: self::INSTR_OR;
		} else {
			throw new CompilerException("{$this->fileName}:{$node->line}:{$node->col}: Assignment operator '{$node->operator}' is not implemented");
			// throw new CompilerRuntimeException("{$this->fileName}:{$node->line}:{$node->col}: Unknown assignment operator '{$node->operator}'");
		}
		// todo: '/=' '%=' '<<=' '>>='

		$this->emit($instructionType, $allocLeft, $allocRight);
		$this->storeAllocationTo($allocLeft, $data->alloc);
		return $allocLeft;
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
		if (1 === 1) {
			throw new CompilerRuntimeException('Statement return is not implemented!');
		}
		$alloc = $this->generateExpression($node->value, true);
		$this->deallocateIfRegister($alloc);
		$this->emitComment("ret (...)");
		$R0 = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => self::REGISTER_R0,
			// 'size' => self::SIZE_64,
		];
		// todo: R0?
		$this->emit(self::INSTR_MOV, $R0, $alloc); // todo: size
		$this->emit(self::INSTR_RET);
	}

	// todo: temporary io.print
	/**
	 * @param NodeStmtReturn|object $node
	 */
	protected function generateStmtIOPrint(object $node): void {
		if (1 === 1) {
			throw new CompilerRuntimeException('Statement io.print is not implemented!');
		}
		/* $alloc = $this->generateExpression($node->value, true);
		$this->deallocateIfRegister($alloc);
		$this->emitComment("io.print (...)");
		$allocLeft = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => self::REGISTER_R0,
			// 'size' => $alloc->size,
		];
		$this->emit('mov', $allocLeft, $alloc);
		$regC = (object) [
			'type' => self::ALLOCATION_REGISTER,
			'value' => self::REGISTER_R0 + 1,
			// 'size' => self::SIZE_64,
		];
		$this->emitLea($regC, 'format_i32'); // todo: size
		// -- $this->pop('rdx');
		// -- echo "    pop     rdx\n";
		// -- echo "    mov     rdx, 5\n";
		$this->emitCall('printf'); */
	}

	protected function swapPrevInstructionCmpOperands(): void {
		$index = count($this->instructions) - 1;
		$instr = $this->instructions[$index];
		if ($instr->instructionType !== self::INSTR_CP) {
			$instruction = self::$instructionsData[$instr->instructionType][1];
			throw new CompilerRuntimeException("Prev instruction is not 'cp', but '{$instruction}'");
		}
		$this->instructions[$index] = (object) [
			'type' => self::EMIT_INSTRUCTION,
			'instructionType' => self::INSTR_CP,
			'arg1' => $instr->arg2, 'arg1Type' => $instr->arg2Type,
			'arg2' => $instr->arg1, 'arg2Type' => $instr->arg1Type,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => '',
			'nArgs' => 2,
		];
	}

	/**
	 * @param Allocation|object $alloc
	 * @param string $label
	 * @param string|null $operator
	 */
	protected function generateCmpJumpToLabel(object $alloc, string $label, ?string $operator = null): void {
		if ($alloc->type === self::ALLOCATION_CMP_FLAG) {
			if ($operator === '==') {
				$this->emitJump(self::INSTR_BREQ, $label);
			} else if ($operator === '!=') {
				$this->emitJump(self::INSTR_BRNE, $label);
			} else if ($operator === '<=') {
				$this->swapPrevInstructionCmpOperands();
				$this->emitJump(self::INSTR_BRGE, $label); // BRGE and swap cmp operands
			} else if ($operator === '>=') {
				$this->emitJump(self::INSTR_BRGE, $label);
			} else if ($operator === '<') {
				$this->emitJump(self::INSTR_BRLT, $label);
			} else if ($operator === '>') {
				$this->swapPrevInstructionCmpOperands();
				$this->emitJump(self::INSTR_BRLT, $label); // BRLT and swap cmp operands
			}
		} else {
			if ($alloc->type === self::ALLOCATION_STACK) {
				$alloc = $this->allocateFreeRegisterFrom($alloc);
				$this->deallocateRegister($alloc);
			}
			// should not be ALLOCATION_IMMEDIATE i.e. `if (1)` (skipping the check for now)
			$this->emit(self::INSTR_TST, $alloc);
			$this->emitJump(self::INSTR_BRNE, $label); // jump if reg!=0
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
				$this->emitJump(self::INSTR_BRNE, $label); // !=
			} else if ($operator === '!=') {
				$this->emitJump(self::INSTR_BREQ, $label); // ==
			} else if ($operator === '<=') {
				$this->swapPrevInstructionCmpOperands();
				$this->emitJump(self::INSTR_BRLT, $label); // >, BRLT and swap cmp operands
			} else if ($operator === '>=') {
				$this->emitJump(self::INSTR_BRLT, $label); // <
			} else if ($operator === '<') {
				$this->emitJump(self::INSTR_BRGE, $label); // >=
			} else if ($operator === '>') {
				$this->swapPrevInstructionCmpOperands();
				$this->emitJump(self::INSTR_BRGE, $label); // <=, BRGE and swap cmp operands
			}
		} else {
			if ($alloc->type === self::ALLOCATION_STACK) {
				$alloc = $this->allocateFreeRegisterFrom($alloc);
				$this->deallocateRegister($alloc);
			}
			// should not be ALLOCATION_IMMEDIATE i.e. `if (1)` (skipping the check for now)
			$this->emit(self::INSTR_TST, $alloc);
			$this->emitJump(self::INSTR_BREQ, $label); // jump if reg==0
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
			// not using zeroAsClr because "if (0)" should not happen
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
			// todo: maybe use relative? ###
			$this->emitJump(self::INSTR_JMP, $labelEndIf);

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
		// todo: maybe use relative? ###
		$this->emitJump(self::INSTR_JMP, $labelCondition);

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
				// not using zeroAsClr because "while (0)" should not happen
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

			// todo: maybe use relative? ###
			$this->emitJump(self::INSTR_JMP, $labelCondition);

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
			'alloc' => $this->allocateStack(self::SIZE_8), // todo: size depending on type
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
	
	/**
	 * @param NodeProgram|object $node
	 * @param string $fileName
	 */
	public function generate(object $node, string $fileName): void {
		$this->fileName = $fileName;
		$this->init();

		$this->emitLabel('start');
		
		$R28 = (object) ['type' => self::ALLOCATION_REGISTER, 'value' => self::REGISTER_R28];
		$R29 = (object) ['type' => self::ALLOCATION_REGISTER, 'value' => self::REGISTER_R29];
		
		// Y <- SP
		$this->emitIn($R28, 0x3D); // SPL
		$this->emitIn($R29, 0x3E); // SPH
		// reserve stack size, will be set in the end
		$this->emit(self::INSTR_SBIW, $R28, $this->createImmediate(0));
		// SP <- Y
		$this->emitOut(0x3E, $R29); // SPH
		$this->emitOut(0x3D, $R28); // SPL
		
		foreach ($node->statements as $statement) {
			$this->generateStmt($statement);
		}
		
		$instr = $this->instructions[3];
		$this->instructions[3] = (object) [
			'type' => $instr->type,
			'instructionType' => $instr->instructionType,
			'arg1' => $instr->arg1, 'arg1Type' => $instr->arg1Type,
			'arg2' => $this->stackPos, 'arg2Type' => $instr->arg2Type,
			'arg3' => 0, 'arg3Type' => self::ARG_NONE,
			'text' => '',
			'nArgs' => 2,
		];
		
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

		/* ['jmp', 0x0002], // 0x940C, 0x0002
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
		['ret'], // 0x9508 */
		
		/*
		:10 0000 00 0C94 0200 259A 2D9A 04D0 2D98 02D0 FBCF 93
		:10 0010 00 0895 29E2 36E9 40E8 4A95 F1F7 3A95 E1F7 83
		:06 0020 00 2A95 D1F7 0895 B6
		:00 0000 01 FF */
		
		/* jmp start
		start:
			sbi DDRB, 5
		loop:
			sbi PORTB, 5
			rcall delay
			cbi PORTB, 5
			rcall delay
			rjmp loop
			ret
		delay:
			ldi r18, 41
			ldi r19, 150
			ldi r20, 128
		delay_loop:
			dec r20
			brne delay_loop
			dec r19
			brne delay_loop
			dec r18
			brne delay_loop
			ret */
		
		/*
		$PORTB = $this->createImmediate(5);
		$DDRB = $this->createImmediate(4);
		
		$this->emitJump(self::INSTR_JMP, 'start');
		
		$this->emitLabel('start');
		$this->emit(self::INSTR_SBI, $DDRB, $this->createImmediate(5));
		$this->emitLabel('loop');
		$this->emit(self::INSTR_SBI, $PORTB, $this->createImmediate(5));
		$this->emitCall(self::INSTR_RCALL, 'delay');
		$this->emit(self::INSTR_CBI, $PORTB, $this->createImmediate(5));
		$this->emitCall(self::INSTR_RCALL, 'delay');
		$this->emitJump(self::INSTR_RJMP, 'loop');
		$this->emit(self::INSTR_RET);
		
		$this->emitLabel('delay');
		$this->emit(
			self::INSTR_LDI,
			(object) ['type' => self::ALLOCATION_REGISTER, 'value' => 18],
			$this->createImmediate(41)
		);
		$this->emit(
			self::INSTR_LDI,
			(object) ['type' => self::ALLOCATION_REGISTER, 'value' => 19],
			$this->createImmediate(150)
		);
		$this->emit(
			self::INSTR_LDI,
			(object) ['type' => self::ALLOCATION_REGISTER, 'value' => 20],
			$this->createImmediate(128)
		);
		$this->emitLabel('delay_loop');
		$this->emit(
			self::INSTR_DEC, (object) ['type' => self::ALLOCATION_REGISTER, 'value' => 20]
		);
		$this->emitJump(self::INSTR_BRNE, 'delay_loop');
		$this->emit(
			self::INSTR_DEC, (object) ['type' => self::ALLOCATION_REGISTER, 'value' => 19]
		);
		$this->emitJump(self::INSTR_BRNE, 'delay_loop');
		$this->emit(
			self::INSTR_DEC, (object) ['type' => self::ALLOCATION_REGISTER, 'value' => 18]
		);
		$this->emitJump(self::INSTR_BRNE, 'delay_loop');
		$this->emit(self::INSTR_RET); */
	}

}