<?php
/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

defined('_JEXEC') or die();

class InterestsModel extends \Sda\Component\Sdajem\Administrator\Model\InterestsModel
{
	public function getInterestsToEvent(int $eventId = null)
	{
		// Create a new query object.
		$db = $this->getDatabase();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				[
					$db->quoteName('a.id'),
					$db->quoteName('a.event_id'),
					$db->quoteName('a.users_user_id'),
					$db->quoteName('a.status'),
					$db->quoteName('a.comment'),
				]
			)
		);
		$query->from($db->quoteName('#__sdajem_interest', 'a'));

		$query->where($db->quoteName('a.event_id') . '=' . $eventId);

		$db->setQuery($query);
		$data = $db->loadObjectList();

		return $data;
	}

}