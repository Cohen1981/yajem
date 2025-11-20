<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Sda\Component\Sdajem\Administrator\Trait;

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
trait ItemTrait
{
	public ?stdClass $item;
	public function __construct()
	{
		$selfReflection = new ReflectionObject($this);
		foreach ($selfReflection->getProperties() as $property)
		{
			$name = $property->getName();
			$this->$name = null;
		}
	}
	public static function createFromArray(array $data): self
	{
		$item = new self();
		$selfReflection = new ReflectionObject($item);
		foreach ($data as $key => $value)
		{
			if ($selfReflection->hasProperty($key)) {
				$item->$key = $value;
			}
		}

		return $item;
	}

	public static function createFromObject(stdClass $data): self
	{
		return self::createFromArray((array) $data);
	}

	public function toArray(): array
	{
		return (array) $this;
	}
}