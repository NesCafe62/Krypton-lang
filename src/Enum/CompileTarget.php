<?php

/**
 * Enum CompileTarget
 */
class CompileTarget {

	public const NONE               = 0;

	public const DUMP_AST           = 1;
	// public const LLVM_IR            = 2;

	// public const FASM_LINUX_x86_64  = 3;
	public const FASM_WIN_x86_64    = 4;
	// public const NASM_LINUX_x86_64  = 5;
	// public const NASM_WIN_x86_64    = 6;

	public const AVR_ATMEGA328      = 7;
	public const AVR_ATMEGA328_HEX  = 8;

	public static $targets = [
		'ast'                   => self::DUMP_AST,
		// 'llvm'                  => self::LLVM_IR,

		// 'fasm-linux-x86-64'     => self::FASM_LINUX_x86_64,
		'fasm-win-x86-64'       => self::FASM_WIN_x86_64,
		// 'nasm-linux-x86-64'     => self::NASM_LINUX_x86_64,
		// 'nasm-win-x86-64'       => self::NASM_WIN_x86_64,

		'avr-atmega328'         => self::AVR_ATMEGA328,
		'avr-atmega328-hex'     => self::AVR_ATMEGA328_HEX,
	];

}