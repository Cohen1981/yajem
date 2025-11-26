<?php
/**
 * @copyright (c) 2025 Alexander Bahlo <abahlo@hotmail.de>
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sda\Component\Sdajem\Administrator\Library\Item;

use Joomla\Registry\Registry;
use ReflectionObject;
use Sda\Component\Sdajem\Administrator\Library\Enums\EventStatusEnum;
use stdClass;

/**
 * @package     Sda\Component\Sdajem\Site\Model\Item
 * @since       1.4.0
 * For programming convenience, the class gives type hinting for the class properties.
 * For frontend use there are additional fields with information from foreign tables
 */
class EventListItem extends EventTableItem
{
	/**
	 * @var EventStatusEnum|null
	 * Enum for the event status. For convenience set at construction of this class.
	 * @since 1.4.0
	 */
	public ?EventStatusEnum $eventStatusEnum;

	/**
	 * @var Registry|null
	 * Registry for the event parameters. For convenience set at construction of this class.
	 * @since 1.4.0
	 */
	public ?Registry $paramsRegistry;

	/**
	 * @var array|null
	 * Array of SVGs for the event. For convenience set at construction of this class.
	 * @since 1.4.0
	 */
	public ?array $svgArray;

	/**
	 * @var string|null
	 * The location name of the event. For convenience set at construction of this class.
	 * @since 1.4.0
	 */
	public ?string $locationName;

	/**
	 * @var string|null
	 * postalCode
	 * The postal code of the event location.
	 * @since 1.5.3
	 */
	public ?string $postalCode;

	/**
	 * @var string|null
	 * organizerName
	 * The name of the event organizer.
	 * @since 1.5.3
	 */
	public ?string $organizerName;

	/**
	 * @var integer|null
	 * attendeeCount
	 * The number of attendees for the event.
	 * @since 1.5.3
	 */
	public ?int $attendeeCount;

	/**
	 * @var integer|null
	 * guestCount
	 * The number of guests for the event.
	 * @since 1.5.3
	 */
	public ?int $guestCount;

	/**
	 * @var integer|null
	 * attendeeFeedbackCount
	 * The number of feedbacks for the event.
	 * @since 1.5.3
	 */
	public ?int $attendeeFeedbackCount;

	/**
	 * @var integer|null
	 * interestCount
	 * The number of interests for the event.
	 * @since 1.5.3
	 */
	public ?int $interestCount;

	/**
	 * @var integer|null
	 * feedbackCount
	 * The number of feedbacks for the event.
	 * @since 1.5.3
	 */
	public ?int $feedbackCount;

	/**
	 * @var string|null
	 * accessLevel
	 * The access level of the event.
	 * @since 1.5.3
	 */
	public ?string $accessLevel;

	/**
	 * @since 1.4.0
	 *
	 * @param   array  $data  the event data as an array
	 *
	 * @return self
	 */
	public static function createFromArray(array $data): self
	{
		$item           = new self;
		$selfReflection = new ReflectionObject($item);

		foreach ($data as $key => $value)
		{
			if ($selfReflection->hasProperty($key))
			{
				switch ($key)
				{
					case 'params':
						if (is_string($value))
						{
							$registry     = new Registry($value);
							$item->params = $registry->toArray();
						}
						else
						{
							$item->params = $value;
						}
						break;
					case 'svg':
						if ($value)
						{
							$item->svgArray = (array) json_decode($data['svg'], true);
						}
						break;
					default:
						$item->$key = $value;
				}
			}
		}

		$item->eventStatusEnum       = EventStatusEnum::from($data['eventStatus']);
		$item->paramsRegistry        = new Registry($data['params']);
		$item->locationName          = $data['locationName'];
		$item->postalCode            = $data['postalCode'];
		$item->organizerName         = $data['organizerName'];
		$item->attendeeCount         = $data['attendeeCount'];
		$item->guestCount            = $data['guestCount'];
		$item->attendeeFeedbackCount = $data['attendeeFeedbackCount'];
		$item->interestCount         = $data['interestCount'];
		$item->feedbackCount         = $data['feedbackCount'];

		return $item;
	}

	/**
	 * @since version
	 *
	 * @param   stdClass  $data  the event as array
	 *
	 * @return self
	 */
	public static function createFromObject(stdClass $data): self
	{
		return self::createFromArray((array) $data);
	}

	/**
	 * @since 1.5.3
	 * Converts the Event to a stdClass for use as form data converting the EventStatusEnum to its value.
	 * @return stdClass
	 */
	public function toFormData(): stdClass
	{
		$object = new stdClass;

		foreach ($this as $key => $value)
		{
			if ($key === 'eventStatus')
			{
				$object->eventStatus = $this->eventStatusEnum->value;
			}
			else
			{
				$object->$key = $value;
			}
		}

		return $object;
	}
}
