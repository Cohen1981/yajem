<?php

namespace Sda\Component\Sdajem\Administrator\Trait;

use ReflectionObject;

/**
 * @author Alexander Bahlo
 * @package Sda\Component\Sdajem\Administrator\Trait
 * @since 1.4.0
 *
 * For programming convinience only
 */
trait ItemTrait
{
	public ?string $typeAlias;
	public ?int $id;
	public ?int $access;
	public ?string $alias;
	public ?string $created;
	public ?int $created_by;
	public ?string $created_by_alias;
	public ?int $checked_out;
	public ?string $checked_out_time;
	public ?int $published;
	public ?string  $publish_up;
	public ?string $publish_down;
	public ?int $state;
	public ?int $ordering;
	public ?string $language;
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

	public static function createFromObject(\stdClass $data): self
	{
		return self::createFromArray((array) $data);
	}

}