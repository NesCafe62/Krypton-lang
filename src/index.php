<?php

require_once('Compiler.php');
require_once('Extensions/ExtCheckTypesAndVariables.php');
require_once('Extensions/ExtEvaluateConstExpressions.php');
require_once('Extensions/ExtRemoveDeadCode.php');

require_once('Generators/GeneratorSrc_php.php');

require_once('Generators/GeneratorDumpAst.php');
require_once('Generators/GeneratorFasm_win_x86_64.php');
require_once('Generators/GeneratorAvr_atmega328.php');

try {
	$printUsage = false;
	if ($argc < 2) {
		$printUsage = true;
		throw new CompilerException("Empty arguments");
	}

	$target = CompileTarget::NONE;
	for ($i = 2; $i < $argc; $i++) {
		$arg = $argv[$i];
		if ($arg === '-t' || $arg === '--target') {
			if ($target === CompileTarget::NONE) {
				if ($i + 1 >= $argc) {
					$printUsage = true;
					throw new CompilerException("Compilation target is not set");
				}
				$argTarget = $argv[$i + 1];
				$target = CompileTarget::$targets[$argTarget];
			}
			$i++;
		} else if (
			strncmp($arg, '-t=', 3) === 0 ||
			strncmp($arg, '--target=', 3) === 0
		) {
			if ($target === CompileTarget::NONE) {
				$argTarget = substr($arg, strpos($arg, '=') + 1);
				$target = CompileTarget::$targets[$argTarget];
			}
		} else if ($arg === '-ast') {
			$target = CompileTarget::DUMP_AST;
		} else {
			$printUsage = true;
			throw new CompilerException("Unknown argument '{$arg}'");
		}
	}

	$fileName = $argv[1];
	if (!is_file($fileName)) {
		$printUsage = true;
		throw new CompilerException("File not found '{$fileName}'");
	}
	$code = file_get_contents($fileName);


	switch ($target) {
		case CompileTarget::SRC_PHP:
			$generator = new GeneratorSrc_php();
			break;

		case CompileTarget::DUMP_AST:
			$generator = new GeneratorDumpAst();
			break;
		case CompileTarget::FASM_WIN_x86_64:
			$generator = new GeneratorFasm_win_x86_64();
			break;
		case CompileTarget::AVR_ATMEGA328:
			$generator = new GeneratorAvr_atmega328();
			break;
		case CompileTarget::AVR_ATMEGA328_HEX:
			$generator = new GeneratorAvr_atmega328(true);
			break;
		default:
			$printUsage = true;
			throw new CompilerException("Compilation target is not set");
	}


	$compiler = new Compiler([
		// TransformMode::AST
		new ExtEvaluateConstExpressions($fileName),

		// TransformMode::AST_ROOT
		new ExtCheckTypesAndVariables($fileName),
		new ExtRemoveDeadCode(),
	]);

	$compiler->compile($code, $fileName, $generator);

} catch (CompilerException $ex) {
	if ($argc > 1) {
		fwrite(STDERR, $ex->getMessage() . "\n");
	}
	if ($printUsage) {
		fwrite(STDERR, "usage:\n");
		fwrite(STDERR, "    php src/index.php <input> [options] > <output>\n");
		fwrite(STDERR, "options:\n");
		fwrite(STDERR, "    -t, --target <target>\n");
		fwrite(STDERR, "                  set compilation target (required)\n");
		fwrite(STDERR, "                  target: ast | fasm-win-x86-64 | avr-atmega328 | avr-atmega328-hex\n");
		fwrite(STDERR, "    -ast\n");
		fwrite(STDERR, "                  dump ast, shorthand of `-t ast`\n");
		fwrite(STDERR, "example: php src/index.php test.kr -t fasm-win-x86-64 > test.asm\n");
		fwrite(STDERR, "example: php src/index.php test.kr -ast > test.ast\n");
		fwrite(STDERR, "example: php src/index.php test.kr -t fasm-win-x86-64 > test.asm && fasm.exe test.asm\n");
	}
	exit(1);
}

// compile to asm for windows, and keep old asm file in case of compile error:
// cmd /c "php src/index.php test.kr -t fasm-win-x86-64 > temp && (move /y temp test.asm > nul) || del temp"

// cmd /c "php src/index.php test.kr -t avr-atmega328 > test-avr.asm"

// compile test.asm with fasm and run it:
// cmd /c "fasm.exe test.asm && test.exe"