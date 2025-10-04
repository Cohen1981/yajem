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

class CommentsModel extends \Sda\Component\Sdajem\Administrator\Model\CommentsModel
{
	public function getCommentsToEvent(int $eventId = null)
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
					$db->quoteName('a.sdajem_event_id'),
					$db->quoteName('a.users_user_id'),
					$db->quoteName('a.comment'),
					$db->quoteName('a.timestamp'),
					$db->quoteName('a.commentReadBy'),
				]
			)
		);
		$query->from($db->quoteName('#__sdajem_comments', 'a'));

		$query->where($db->quoteName('a.sdajem_event_id') . '= :eventId');
		$query->bind(':eventId', $eventId);

		$db->setQuery($query);
		$data = $db->loadObjectList();

		return $data;
	}
}