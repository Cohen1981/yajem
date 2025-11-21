<?php

namespace Sda\Component\Sdajem\Site\Model\Collections;

use ArrayIterator;
use IteratorAggregate;
use Sda\Component\Sdajem\Administrator\Trait\ItemsTrait;
use Sda\Component\Sdajem\Site\Model\Item\Attending;
use Sda\Component\Sdajem\Site\Model\Item\Event;
use Sda\Component\Sdajem\Site\Model\Item\Fitting;

/**
 * @since 1.4.3
 * @template-implements IteratorAggregate<Fitting>
 */
class FittingsCollection extends \ArrayObject implements IteratorAggregate {

	use ItemsTrait;

	private array $items = [];

	public function __construct(array $items) {

		parent::__construct();

		foreach ($items as $item) {
			$this->items[] = Event::createFromObject($item);
		}
	}

	static function fromArray(array $items):self {
		return new self(...$items);
	}
}