<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @package     Sda\Component\Sdajem\Administrator\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Helper;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;

abstract class InterestHelper
{
	public static function getInterestStatusToEvent(int $userId=null, int $eventId)
	{
		if (!$userId) {
			$userId = Factory::getApplication()->getIdentity()->id;
		}

		try
		{
			$db    = Factory::getContainer()->get(DatabaseInterface::class);
			$query = $db->getQuery(true);

			$query->select('a.*')
				->from($db->quoteName('#__sdajem_interest', 'a'))
				->where($db->quoteName('a.users_user_id') . ' = :userId')
				->extendWhere('AND', $db->quoteName('a.event_id') . ' = :eventId');

			$query->bind(':userId', $userId)
				->bind(':eventId', $eventId);

			$db->setQuery($query);
			$data = $db->loadObject();

			if (empty($data))
			{
				throw new Exception(Text::_('COM_SDAJEM_ERROR_INTEREST_NOT_FOUND'), 404);
			}
		}
		catch (Exception $e)
		{
			return null;
		}

		return $data;
	}
}