<?php
/**
 * @copyright (c) 2025 Alexander Bahlo <abahlo@hotmail.de>
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sda\Component\Sdajem\Site\Model\Collections;

use IteratorAggregate;
use Sda\Component\Sdajem\Administrator\Library\Item\Event;
use Sda\Component\Sdajem\Administrator\Library\Trait\ItemsTrait;

/**
 * @since 1.4.3
 * @template-implements IteratorAggregate<Event>
 */
class EventsCollection extends \ArrayObject implements IteratorAggregate
{

	use ItemsTrait;

	/**
	 * @var array $items the array of items
	 * @since 1.5.3
	 */
	private array $items = [];

	/**
	 * Constructor method for initializing the class with a list of items.
	 *
	 * @since 1.5.3
	 *
	 * @param   array  $items  An array of items to be processed and added to the class.
	 */
	public function __construct(array $items)
	{
		parent::__construct();

		foreach ($items as $item)
		{
			$this->items[] = Event::createFromObject($item);
		}
	}
}
