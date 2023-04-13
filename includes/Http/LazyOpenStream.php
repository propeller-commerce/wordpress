<?php

namespace Propeller\Http;

use Psr\Http\Message\StreamInterface;

class LazyOpenStream implements StreamInterface{

	use StreamDecoratorTrait;

	/** @var string */
	private $filename;

	/** @var string */
	private $mode;

	/**
	 * @param string $filename File to lazily open
	 * @param string $mode     fopen mode to use when opening the stream
	 */
	public function __construct(string $filename, string $mode)
	{
		$this->filename = $filename;
		$this->mode = $mode;
	}

	/**
	 * Creates the underlying stream lazily when required.
	 */
	protected function createStream(): StreamInterface
	{
		return Utils::streamFor(Utils::tryFopen($this->filename, $this->mode));
	}

}