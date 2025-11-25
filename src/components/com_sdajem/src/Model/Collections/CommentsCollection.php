<?php
/**
 * @copyright (c) 2025 Alexander Bahlo <abahlo@hotmail.de>
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sda\Component\Sdajem\Site\Model\Collections;

use IteratorAggregate;
use Sda\Component\Sdajem\Administrator\Library\Item\Comment;
use Sda\Component\Sdajem\Administrator\Library\Trait\ItemsTrait;

/**
 * @since 1.4.3
 * @template-implements IteratorAggregate<Comment>
 */
class CommentsCollection extends \ArrayObject implements IteratorAggregate
{
	use ItemsTrait;

	private array $items = [];

	public function __construct(array $items)
	{
		parent::__construct();

		foreach ($items as $item)
		{
			$this->items[] = Comment::createFromObject($item);
		}
	}

	static function fromArray(array $items): self
	{
		return new self(...$items);
	}
}
