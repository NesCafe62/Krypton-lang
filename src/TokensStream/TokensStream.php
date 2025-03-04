<?php

class TokensStream implements TokensStreamInterface {

	/** @var Token[] */
	protected $tokens;

	/** @var Token[] */
	protected $resultTokens;

	/** @var int */
	protected $resultLength;

	/** @var int */
	protected $srcLength;

	/** @var int */
	protected $offset;

	/** @var int */
	protected $index;

	/** @var bool */
	protected $updated;

	public function deleteTokens(int $from, int $length): void {
		$to = $from + $length;
		if ($to + $this->offset > $this->srcLength) {
			throw new CompilerRuntimeException("TokensStream delete tokens at '{$from}:{$to}' is out of range");
		}

		$this->updated = true;
		if ($from >= $this->resultLength) {
			$this->extendTo($from - 1);
			$this->offset += $length;
			return;
		}

		// length to delete from resultTokens
		$len = ($to > $this->resultLength)
			? $this->resultLength - $from
			: $length;
		$this->resultLength -= $len;
		// $this->index -= $len;
		$this->index = $from;
		$this->offset += $length;
		array_splice($this->resultTokens, $from, $len);
	}

	/**
	 * @param int $from
	 * @param int $length
	 * @param Token[] $tokens
	 */
	public function replaceTokens(int $from, int $length, array $tokens): void {
		$to = $from + $length;
		if ($to + $this->offset > $this->srcLength) {
			throw new CompilerRuntimeException("TokensStream replace tokens at '{$from}:{$to}' is out of range");
		}

		$this->updated = true;
		if ($from >= $this->resultLength) {
			$this->extendTo($from - 1);
			array_splice($this->resultTokens, $from, 0, $tokens);
		} else {
			// length to delete from resultTokens
			$len = ($to > $this->resultLength)
				? $this->resultLength - $from
				: $length;
			$this->resultLength -= $len;
			// $this->index -= $len;
			$this->index = $from;
			array_splice($this->resultTokens, $from, $len, $tokens);
		}

		$insertLength = count($tokens);
		$this->resultLength += $insertLength;
		$this->offset += $length - $insertLength;
	}

	/**
	 * @param int $at
	 * @param Token[] $tokens
	 */
	public function insertTokens(int $at, array $tokens): void {
		if ($at + $this->offset > $this->srcLength) {
			throw new CompilerRuntimeException("TokensStream insert tokens at '{$at}' is out of range");
		}

		$this->extendTo($at - 1);
		array_splice($this->resultTokens, $at, 0, $tokens);
		$insertLength = count($tokens);
		$this->resultLength += $insertLength;
		$this->offset -= $insertLength;

		$this->index = $at;
		$this->updated = true;
	}

	protected function extendTo(int $index): void {
		if ($index < $this->resultLength) {
			return;
		}
		for ($i = $this->resultLength + $this->offset; $i <= $index + $this->offset; $i++) {
			$this->resultTokens[] = $this->tokens[$i];
		}
		$this->resultLength = $index + 1;
	}

	public function tokenAt(int $index): ?Token {
		if ($index < $this->resultLength) {
			return $this->resultTokens[$index];
		}
		if ($index + $this->offset >= $this->srcLength) {
			// throw new CompilerRuntimeException("TokensStream tokenAt '{$index}' is out of range");
			return null;
		}
		return $this->tokens[$index + $this->offset];
	}

	/**
	 * @param Token[] $tokens
	 * @param CompilerExtension[] $extensions
	 * @return Token[]
	 */
	public function transformTokens(array $tokens, array $extensions): array {
		$extensions = array_filter($extensions, function(CompilerExtension $extension) {
			return $extension->transformMode & TransformMode::TOKENS;
		});

		$extensionsCount = count($extensions);
		if ($extensionsCount === 0) {
			return $tokens;
		}

		$this->tokens = $tokens;
		$this->resultTokens = [];
		$this->resultLength = 0;
		$srcLength = count($tokens);
		$this->srcLength = $srcLength;
		$this->offset = 0;
		$this->index = 0;
		$this->updated = false;

		while ($this->index + $this->offset < $srcLength) {
			$this->extendTo($this->index);
			$token = $this->resultTokens[$this->index];

			foreach ($extensions as $i => $extension) {
				$extension->transformToken($token, $this->index, $this);

				if ($this->updated) {
					if ($i < $extensionsCount) {
						break;
					}
					$this->updated = false;
				}
			}
			if ($this->updated) {
				$this->updated = false;
				continue;
			}

			$this->index++;
		}
		return $this->resultTokens;
	}

}

/* class ArrayView {

	/ ** @var object * /
	protected $itemsCont;

	/ ** @var int * /
	protected $start;

	/ ** @var int * /
	protected $end;

	public function __construct(object $itemsCont, int $start, int $end) {
		$this->itemsCont = $itemsCont;
		$this->start = $start;
		$this->end = $end;
	}

	public static function fromArray(array $items): ArrayView {
		$itemsCont = (object) ['items' => $items];
		return new ArrayView($itemsCont, 0, count($items));
	}

	public function length(): int {
		return $this->end - $this->start;
	}

	public function subArray(int $start, int $end): ArrayView {
		if ($this->start + $end > $this->end) {
			throw new CompilerRuntimeException("ArrayView end '{$end}' is out of range");
		}
		return new ArrayView($this->itemsCont, $this->start + $start, $this->start + $end);
	}

	public function at(int $index): Token {
		return $this->itemsCont->items[$this->start + $index];
	}

	public function appendTo(array &$arr): void {
		$items = $this->itemsCont->items;
		for ($i = $this->start; $i < $this->end; $i++) {
			$arr[] = $items[$i];
		}
	}

} */

/* class TokensStream2 {

	/ ** @var ArrayView[] * /
	protected $rope;

	/ ** @var int * /
	protected $totalLength;

	/ **
	 * @param Token[] $tokens
	 * /
	public function __construct(array $tokens) {
		$this->rope = [
			ArrayView::fromArray($tokens)
		];
		$this->totalLength = count($tokens);
	}

	public function deleteTokens(int $from, int $length): void {
		$to = $from + $length;
		if ($to > $this->totalLength) {
			throw new CompilerRuntimeException("TokensStream delete tokens at '{$from}:{$to}' is out of range");
		}
		$this->totalLength -= $length;
		$index = 0;
		$i = 0;
		foreach ($this->rope as $view) {
			$viewLength = $view->length();
			$localFrom = $from - $index;
			if ($localFrom >= $viewLength) {
				$index += $viewLength;
				$i++;
				continue;
			}
			$localTo = $to - $index;

			if ($localFrom === 0 && $localTo >= $viewLength) {
				array_splice($this->rope, $i, 1);
				$i--;
			} else if ($localFrom === 0) { // && $localTo < $viewLength
				$this->rope[$i] = $view->subArray($localTo, $viewLength);
			} else if ($localTo >= $viewLength) {
				$this->rope[$i] = $view->subArray(0, $localFrom);
			} else {
				$before = $view->subArray(0, $localFrom);
				$after = $view->subArray($localTo, $viewLength);
				array_splice($this->rope, $i, 1, [$before, $after]);
			}

			if ($localTo <= $viewLength) {
				return;
			}
			$index += $viewLength;
			$from = $index;
			$i++;
		}
	}

	/ **
	 * @param int $from
	 * @param int $length
	 * @param Token[] $tokens
	 * /
	public function replaceTokens(int $from, int $length, array $tokens): void {
		$to = $from + $length;
		if ($to > $this->totalLength) {
			throw new CompilerRuntimeException("TokensStream replace tokens at '{$from}:{$to}' is out of range");
		}
		// $this->deleteTokens($at, $length);
		// $this->insertTokens($at, $tokens);

		if ($length === 0) {
			$this->insertTokens($from, $tokens);
			return;
		}

		$insertView = ArrayView::fromArray($tokens);
		$this->totalLength += count($tokens);

		$this->totalLength -= $length;
		$index = 0;
		$i = 0;
		foreach ($this->rope as $view) {
			$viewLength = $view->length();
			$localFrom = $from - $index;
			if ($localFrom >= $viewLength) {
				$index += $viewLength;
				$i++;
				continue;
			}
			$localTo = $to - $index;

			if ($localFrom === 0 && $localTo >= $viewLength) {
				if ($localTo === $viewLength) {
					$this->rope[$i] = $insertView;
					return;
				}
				array_splice($this->rope, $i, 1);
				$i--;
			} else if ($localFrom === 0) { // && $localTo < $viewLength
				$this->rope[$i] = $view->subArray($localTo, $viewLength);
				// insert before $i-th view
				array_splice($this->rope, $i, 0, $insertView);
			} else if ($localTo >= $viewLength) {
				$this->rope[$i] = $view->subArray(0, $localFrom);
				if ($localTo === $viewLength) {
					// insert after $i-th view
					array_splice($this->rope, $i + 1, 0, $insertView);
					return;
				}
			} else {
				$before = $view->subArray(0, $localFrom);
				$after = $view->subArray($localTo, $viewLength);
				array_splice($this->rope, $i, 1, [$before, $insertView, $after]);
			}

			if ($localTo <= $viewLength) {
				return;
			}
			$index += $viewLength;
			$from = $index;
			$i++;
		}
	}

	/ **
	 * @param int $at
	 * @param Token[] $tokens
	 * /
	public function insertTokens(int $at, array $tokens): void {
		$totalLength = $this->totalLength;
		if ($at > $totalLength) {
			throw new CompilerRuntimeException("TokensStream insert tokens at '{$at}' is out of range");
		}
		$insertView = ArrayView::fromArray($tokens);
		$this->totalLength += count($tokens);
		if ($at === $totalLength) {
			$this->rope[] = $insertView;
			return;
		}
		if ($at === 0) {
			array_unshift($this->rope, $insertView);
			return;
		}
		$index = 0;
		foreach ($this->rope as $i => $view) {
			$viewLength = $view->length();
			$localIndex = $at - $index;
			if ($localIndex >= $viewLength) {
				$index += $viewLength;
				continue;
			}
			if ($localIndex === 0) {
				array_splice($this->rope, $i, 0, [$insertView]);
				return;
			}
			$before = $view->subArray(0, $localIndex);
			$after = $view->subArray($localIndex, $viewLength);
			array_splice($this->rope, $i, 1, [$before, $insertView, $after]);
			return;
		}
	}

	/ **
	 * @param CompilerExtension[] $extensions
	 * @return Token[]
	 * /
	public function transformTokens(array $extensions): array {
		foreach ($this->rope as $view) {
			for ($i = 0; $i < $view->length(); $i++) {
				$token = $view->at($i);
				foreach ($extensions as $extension) {
					$extension->transformToken($token, $this);
				}
			}
		}
		return $this->getTokens();
	}

	/ **
	 * @return Token[]
	 * /
	protected function getTokens(): array {
		$tokens = [];
		foreach ($this->rope as $view) {
			$view->appendTo($tokens);
		}
		return $tokens;
	}

} */