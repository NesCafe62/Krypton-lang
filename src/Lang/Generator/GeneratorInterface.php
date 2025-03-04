<?php

interface GeneratorInterface {

	function generate(object $node, string $fileName): void;

}