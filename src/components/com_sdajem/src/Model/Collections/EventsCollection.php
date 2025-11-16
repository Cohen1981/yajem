<?php

namespace Sda\Component\Sdajem\Site\Model\Collections;

use ArrayIterator;
use IteratorAggregate;
use Sda\Component\Sdajem\Site\Model\Item\Event;

/**
 * @since 1.4.3
 * @template-implements IteratorAggregate<Event>
 */
class EventsCollection extends \ArrayObject implements IteratorAggregate {
	private array $events = [];

	public function __construct(array $ratings) {

		parent::__construct();

		foreach ($ratings as $rating) {
			$this->events[] = Event::createFromObject($rating);
		}
	}

	static function fromArray(array $events) {
		return new self(...$events);
	}

	public function getIterator():ArrayIterator
	{
		return new ArrayIterator($this->events);
	}
}