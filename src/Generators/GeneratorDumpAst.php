<?php

class GeneratorDumpAst implements GeneratorInterface {

	/**
	 * @param NodeProgram|object $node
	 * @param string $fileName
	 */
	public function generate(object $node, string $fileName): void {
		echo Lang::displayNode($node);
	}

}
