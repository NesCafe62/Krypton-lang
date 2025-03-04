<?php

abstract class CompilerExtension {

	public $transformMode = TransformMode::NONE;

	public function transformToken(Token $current, int $index, TokensStreamInterface $tokens): void { }

	/**
	 * @param Node|object $node
	 * @return null|Node
	 */
	public function transformASTNode(object $node): ?object {
		return $node;
	}

	/**
	 * @param NodeProgram|object $node
	 * @return NodeProgram
	 */
	public function transformASTRootNode(object $node): object {
		return $node;
	}

}