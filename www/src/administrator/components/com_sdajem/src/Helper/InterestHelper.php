<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Service\Provider\Database;
use Joomla\Database\DatabaseInterface;
use Symfony\Component\VarDumper\Cloner\Data;

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
				->where('a.users_user_id = ' . (int) $userId)
				->extendWhere('AND', 'a.event_id = ' . (int) $eventId);

			$db->setQuery($query);
			$data = $db->loadObject();

			if (empty($data))
			{
				throw new \Exception(Text::_('COM_SDAJEM_ERROR_INTEREST_NOT_FOUND'), 404);
			}
		}
		catch (\Exception $e)
		{
			return null;
		}

		return $data;
	}
}