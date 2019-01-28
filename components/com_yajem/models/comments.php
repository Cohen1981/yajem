<?php
/**
 * @package     Yajem.Site
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemModelComments extends ListModel
{
	/**
	 * Get the Attendees for an event
	 *
	 * @param   int $eventId Event Id
	 *
	 * @return mixed List of Attendees
	 *
	 * @since 1.0
	 */
	public function getComments($eventId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select('a.id, a.userId, a.eventId, a.comment, a.timestamp');
		$query->from('#__yajem_comments AS a');
		$query->where('a.eventId = ' . (int) $eventId);

		$query->order('a.timestamp DESC');

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	public function getCommentCount($eventId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select('count(*) as commentCount');
		$query->from('#__yajem_comments AS a');
		$query->where('a.eventId = ' . (int) $eventId);

		$db->setQuery($query);

		return $db->loadResult();
	}
}
