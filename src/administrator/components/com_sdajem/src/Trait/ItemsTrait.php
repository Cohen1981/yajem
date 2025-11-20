<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Sda\Component\Sdajem\Administrator\Trait;

use ArrayIterator;
use IteratorAggregate;
use Joomla\CMS\Factory;
use ReflectionObject;
use stdClass;

/**
 * @author Alexander Bahlo
 * @package Sda\Component\Sdajem\Administrator\Trait
 * @since 1.4.0
 *
 * For programming convinience only
 */
trait ItemsTrait
{
	private array $items = [];

	public function getIterator():ArrayIterator
	{
		return new ArrayIterator($this->items);
	}

	public function count():int
	{
		return count($this->items);
	}

}