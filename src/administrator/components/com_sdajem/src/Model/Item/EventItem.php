<?php

namespace Sda\Component\Sdajem\Administrator\Model\Item;

use Sda\Component\Sdajem\Administrator\Trait\ItemTrait;

/**
 * @since       1.4.0
 * @package     Sda\Component\Sdajem\Administrator\Model\Item
 *
 * For programming convenience, the class gives type hinting for the class properties.
 */
class EventItem extends \stdClass
{
	use ItemTrait;

	public ?string $title;
	public ?string $description;
	public ?string $url;
	public ?string $image;
	public ?int $locationId;
	public ?int $hostId;
	public ?int $organizerId;
	public ?string $startDateTime;
	public ?string $endDateTime;
	public ?int $allDayEvent;
	public ?int $eventStatus;
	public ?int $eventCancelled;
	public ?int $catid;
	public ?string $params;
	public ?string $svg;
	public ?string $registerUntil;

}