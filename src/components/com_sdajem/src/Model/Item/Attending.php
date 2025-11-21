<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Sda\Component\Sdajem\Site\Model\Item;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;
use Sda\Component\Sdajem\Administrator\Trait\ItemTrait;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Enums\IntAttStatusEnum;
use stdClass;

/**
 * @since       1.5.2
 * @package     Sda\Component\Sdajem\Site\Model\Item
 *
 * For programming convenience, the class gives type hinting for the class properties.
 */
class Attending extends \stdClass
{
	use ItemTrait;

	public ?int $id;
	public ?int $access;
	public ?string $alias;
	public ?int $state;
	public ?int $ordering;
	public ?int $event_id;
	public ?int $users_user_id;
	public IntAttStatusEnum $status = IntAttStatusEnum::NA;
	public ?string $fittings;
	public EventStatusEnum $event_status = EventStatusEnum::PLANING;

	public function __construct()
	{
		$this->status = IntAttStatusEnum::NA;
		$this->event_status = EventStatusEnum::PLANING;
	}

	public static function getAttendingToEvent(int $userId=null, int $eventId):self
	{
		if (!$userId) {
			$userId = Factory::getApplication()->getIdentity()->id;
		}

		try
		{
			$db    = Factory::getContainer()->get(DatabaseInterface::class);
			$query = $db->getQuery(true);

			$query->select('a.*')
				->from($db->quoteName('#__sdajem_attendings', 'a'))
				->where($db->quoteName('a.users_user_id') . ' = :userId')
				->extendWhere('AND', $db->quoteName('a.event_id') . ' = :eventId');

			$query->bind(':userId', $userId)
				->bind(':eventId', $eventId);

			$db->setQuery($query);
			$data = $db->loadObject();

			if (empty($data))
			{
				throw new Exception(Text::_('COM_SDAJEM_ERROR_ATTENDING_NOT_FOUND'), 404);
			}
			$data->status = IntAttStatusEnum::from($data->status);
			$data->event_status = EventStatusEnum::from($data->event_status);
		}
		catch (Exception $e)
		{
			$data = new self();
			$data->status = IntAttStatusEnum::NA;
			$data->event_status = EventStatusEnum::PLANING;
		}

		return self::createFromObject($data);
	}
}