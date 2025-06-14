<?php
require_once('Enum/CompileTarget.php');

require_once('Lang/Exceptions/CompilerException.php');
require_once('Lang/Exceptions/CompilerRuntimeException.php');

require_once('Lang/Extensions/TransformMode.php');
require_once('Lang/Extensions/CompilerExtension.php');

require_once('Lang/Tokenizer/TokenType.php');
require_once('Lang/Tokenizer/Token.php');
require_once('Lang/Parser/NodeType.php');
require_once('Lang/Parser/Node.php');

require_once('Lang/Lang.php');

require_once('Tokenizer.php');
require_once('Parser.php');

require_once('TokensStream/TokensStreamInterface.php');
require_once('TokensStream/TokensStream.php');

require_once('Lang/Generator/Allocation.php');
require_once('Lang/Generator/Instruction.php');
require_once('Lang/Generator/GeneratorInterface.php');




class Compiler {

	/** @var CompilerExtension[] */
	protected $extensions = [];

	/** @var Tokenizer */
	protected $tokenizer;

	/** @var TokensStream */
	protected $tokensStream;

	/** @var Parser */
	protected $parser;

	/**
	 * @param CompilerExtension[] $extensions
	 */
	public function __construct(array $extensions) {
		$this->extensions = $extensions;
		$this->tokenizer = new Tokenizer();
		$this->tokensStream = new TokensStream();
		$this->parser = new Parser();
	}

	public function compile(string $code, string $fileName, GeneratorInterface $generator): void {
		$tokens = $this->tokenizer->tokenize($code, $fileName);
		/* foreach ($tokens as $token) {
			echo Lang::displayToken($token->token, $token->value) . " {$token->line}:{$token->col}\n";
		}
		exit; */

		$tokens = $this->tokensStream->transformTokens($tokens, $this->extensions);

		$node = $this->parser->parse($tokens, $fileName, $this->extensions);

		$generator->generate($node, $fileName);
	}

}