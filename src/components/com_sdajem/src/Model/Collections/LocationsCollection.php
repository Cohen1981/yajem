<?php

namespace Sda\Component\Sdajem\Site\Model\Collections;

use ArrayIterator;
use IteratorAggregate;
use Sda\Component\Sdajem\Site\Model\Item\Event;
use Sda\Component\Sdajem\Site\Model\Item\Location;

/**
 * @since 1.4.3
 * @template-implements IteratorAggregate<Location>
 */
class LocationsCollection extends \ArrayObject implements IteratorAggregate {
	private array $locations = [];

	public function __construct(array $ratings) {

		parent::__construct();

		foreach ($ratings as $rating) {
			$this->locations[] = Location::createFromObject($rating);
		}
	}

	public function getIterator():ArrayIterator
	{
		return new ArrayIterator($this->locations);
	}
}