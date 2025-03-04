<?php

class GeneratorDumpAst implements GeneratorInterface {

	function generate(object $node, string $fileName): void {
		echo Lang::displayNode($node);
	}

}
