<?php

interface GeneratorInterface {

	/**
	 * @param NodeProgram|object $node
	 * @param string $fileName
	 * @return void
	 */
	function generate(object $node, string $fileName): void;

}