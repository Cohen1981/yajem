<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Sda\Component\Sdajem\Site\Model\Item;

use Joomla\Registry\Registry;
use ReflectionObject;
use Sda\Component\Sdajem\Administrator\Trait\ItemTrait;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use stdClass;

/**
 * @since       1.4.0
 * @package     Sda\Component\Sdajem\Site\Model\Item
 *
 * For programming convenience, the class gives type hinting for the class properties.
 */
class Event extends \stdClass
{
	use ItemTrait;

	public ?string $title;
	public ?string $description;
	public ?string $url;
	public ?string $image;
	public ?int $sdajem_location_id;
	public ?int $hostId;
	public ?int $organizerId;
	public ?string $startDateTime;
	public ?string $endDateTime;
	public ?int $allDayEvent;
	public ?EventStatusEnum $eventStatus;
	public ?int $eventCancelled;
	public ?int $catid;
	public ?string $params;
	public ?Registry $paramsRegistry;
	public ?array $svg;
	public ?string $registerUntil;

	public ?string $location_name;
	public ?string $postalCode;
	public ?string $organizerName;
	public ?int $attendeeCount;
	public ?int $guestCount;
	public ?int $attendeeFeedbackCount;
	public ?int $interestCount;
	public ?int $feedbackCount;

	public ?string $typeAlias;
	public ?int $id;
	public ?int $access;
	public ?string $alias;
	public ?string $created;
	public ?int $created_by;
	public ?string $created_by_alias;
	public ?int $published;
	public ?string  $publish_up;
	public ?string $publish_down;
	public ?int $state;
	public ?int $ordering;
	public ?string $language;

	public static function createFromArray(array $data): self
	{
		$item = new self();
		$selfReflection = new ReflectionObject($item);
		foreach ($data as $key => $value)
		{
			if ($selfReflection->hasProperty($key)) {
				if ($key === 'eventStatus' && is_int($value))
				{
					$item->$key = EventStatusEnum::from($value);
				}
				else
				{
					$item->$key = $value;
				}
			}
		}

		return $item;
	}

	public static function createFromObject(stdClass $data): self
	{
		return self::createFromArray((array) $data);
	}
}