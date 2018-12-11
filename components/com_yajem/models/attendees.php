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
class YajemModelAttendees extends ListModel
{
	/**
	 * Get the Attendees for an event
	 *
	 * @param   int $eventId Event Id
	 *
	 * @return mixed List of Attendees
	 *
	 * @since version
	 */
	public function getAttendees($eventId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select('a.id, a.userId, a.status,a.comment');
		$query->from('#__yajem_attendees AS a');
		$query->where('a.eventId = ' . (int) $eventId);

		$query->select('u.name AS attendee');
		$query->join('LEFT', '#__users AS u ON u.id = userId');

		$query->select('cd.name AS clearName, cd.image AS avatar');
		$query->join('LEFT', '#__contact_details AS cd on cd.user_id = userId');

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get the Number of Attendees for an event
	 * @param   int $eventId The Event id
	 *
	 * @return integer AttendeeNumber
	 *
	 * @since version
	 */
	public function getAttendeeNumber($eventId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select('count(*) as attendeeNumber');
		$query->from('#__yajem_attendees AS a');
		$query->where('a.status=1 AND a.eventId = ' . (int) $eventId);

		$db->setQuery($query);

		return $db->loadResult();
	}
}
