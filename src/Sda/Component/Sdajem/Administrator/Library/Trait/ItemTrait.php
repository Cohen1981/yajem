<?php
/**
 * @copyright (c) 2025 Alexander Bahlo <abahlo@hotmail.de>
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sda\Component\Sdajem\Administrator\Library\Trait;

use ReflectionObject;
use stdClass;

/**
 * @package Sda\Component\Sdajem\Administrator\Trait
 * @author  Alexander Bahlo <abahlo@hotmail.de>
 * @since   1.4.0
 * For programming convenience only
 */
trait ItemTrait
{
	/**
	 * @since 1.5.3
	 */
	public function __construct()
	{
		$selfReflection = new ReflectionObject($this);

		foreach ($selfReflection->getProperties() as $property)
		{
			$name        = $property->getName();
			$this->$name = null;
		}
	}

	/**
	 * @since 1.5.3
	 *
	 * @param   array  $data  the data array to convert
	 *
	 * @return \Sda\Component\Sdajem\Administrator\Library\Item\Attending|ItemTrait|\Sda\Component\Sdajem\Administrator\Library\Item\Comment|\Sda\Component\Sdajem\Administrator\Library\Item\Event|\Sda\Component\Sdajem\Administrator\Library\Item\Fitting|\Sda\Component\Sdajem\Administrator\Library\Item\Location
	 */
	public static function createFromArray(array $data): self
	{
		$item           = new self;
		$selfReflection = new ReflectionObject($item);

		foreach ($data as $key => $value)
		{
			if ($selfReflection->hasProperty($key))
			{
				$item->$key = $value;
			}
		}

		return $item;
	}

	/**
	 * @since 1.5.3
	 *
	 * @param   stdClass  $data  The class to convert
	 *
	 * @return \Sda\Component\Sdajem\Administrator\Library\Item\Attending|ItemTrait|\Sda\Component\Sdajem\Administrator\Library\Item\Comment|\Sda\Component\Sdajem\Administrator\Library\Item\Event|\Sda\Component\Sdajem\Administrator\Library\Item\Fitting|\Sda\Component\Sdajem\Administrator\Library\Item\Location
	 */
	public static function createFromObject(stdClass $data): self
	{
		return self::createFromArray((array) $data);
	}

	/**
	 * @since 1.5.3
	 * @return array
	 */
	public function toArray(): array
	{
		return (array) $this;
	}
}
