<?php
if ($argc < 2) {
	fwrite(STDERR, "compile to asm: php src\index.php test.kr > test.asm\n");
	fwrite(STDERR, "compile and dump ast: php src\index.php test.kr -ast > test.ast\n");
	fwrite(STDERR, "compile to exe with fasm: php src\index.php test.kr > test.asm && fasm.exe test.asm\n");
	exit(1);
}

require_once('Compiler.php');
require_once('Extensions/ExtCheckTypesAndVariables.php');
require_once('Extensions/ExtEvaluateConstExpressions.php');
require_once('Extensions/ExtRemoveDeadCode.php');

require_once('Generators/GeneratorDumpAst.php');
require_once('Generators/GeneratorFasm_win_x86_64.php');

// for windows
// compile to asm (but keep old asm file in case of compile error):
// php src\index.php test.kr > temp && (move /y temp test.asm > nul) || del temp

// compile test.asm with fasm and run it:
// fasm.exe test.asm && test.exe

$isAst = ($argv[2] ?? null === '-ast');
$fileName = $argv[1];
$code = file_get_contents($fileName);

$compiler = new Compiler([
	// TransformMode::AST
	new ExtEvaluateConstExpressions(),

	// TransformMode::AST_ROOT
	new ExtCheckTypesAndVariables($fileName),
	new ExtRemoveDeadCode(),
]);

$generator = $isAst
	? new GeneratorDumpAst()
	: new GeneratorFasm_win_x86_64();

$compiler->compile($code, $fileName, $generator);