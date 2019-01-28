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
	 * @since 1.0
	 */
	public function getAttendees($eventId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select('a.id, a.userId, a.status');
		$query->from('#__yajem_attendees AS a');
		$query->where('a.eventId = ' . (int) $eventId);

		$db->setQuery($query);

		$attendees = $db->loadObjectList();

		foreach ($attendees as $attendee) {
				$attendee->attendee = YajemHelperAdmin::getUser($attendee->userId);
		}

		return (object) $attendees;
	}

	/**
	 * Get the Number of Attendees for an event
	 * @param   int $eventId The Event id
	 *
	 * @return integer AttendeeNumber
	 *
	 * @since 1.0
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
