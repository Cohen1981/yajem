<?php

namespace Sda\Component\Sdajem\Site\Model\Collections;

use ArrayIterator;
use IteratorAggregate;
use Sda\Component\Sdajem\Administrator\Trait\ItemsTrait;
use Sda\Component\Sdajem\Site\Model\Item\Comment;
use Sda\Component\Sdajem\Site\Model\Item\Event;

/**
 * @since 1.4.3
 * @template-implements IteratorAggregate<Comment>
 */
class CommentsCollection extends \ArrayObject implements IteratorAggregate {
	use ItemsTrait;

	private array $items = [];

	public function __construct(array $items) {

		parent::__construct();

		foreach ($items as $item)
		{
			$this->items[] = Comment::createFromObject($item);
		}
	}

	static function fromArray(array $items):CommentsCollection
	{
		return new self(...$items);
	}
}