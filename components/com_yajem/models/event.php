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

JHtml::_('stylesheet', JUri::root() . 'media/com_yajem/css/style.css');

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\Utilities\ArrayHelper;
use Joomla\Event\Dispatcher;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemModelEvent extends ItemModel
{
	/**
	 * @param   null $ordering  Null
	 * @param   null $direction Null
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app  = JFactory::getApplication('com_yajem');

		$id = JFactory::getApplication()->input->get('id');

		$this->setState('item.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('item.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	 * @param   null $id Id of the Event to load
	 *
	 * @return  boolean|object
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function &getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('item.id');
			}

			// Get a level row instance.
			$table = $this->getTable('Event', 'YajemTable');

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Convert the JTable to a clean JObject.
				$properties  = $table->getProperties(1);
				$this->_item = ArrayHelper::toObject($properties, 'JObject');
			}
		}

		if (isset($this->_item->createdBy))
		{
			$this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
		}

		if (isset($this->_item->modifiedBy))
		{
			$this->_item->modified_by_name = JFactory::getUser($this->_item->modified_by)->name;
		}

		if (isset($this->_item->organizerId))
		{
			$db = JFactory::getDbo();
			$conQuery = $db->getQuery(true);
			$conQuery->select('cd.name as name, cd.user_id as user_id, u.email as email')
				->from('#__contact_details AS cd')
				->where('cd.id = ' . $this->_item->organizerId)
				->join('left', '#__users as u on u.id = cd.user_id');
			$db->setQuery($conQuery);
			$this->_item->organizer = $db->loadObject();
			$this->_item->organizerLink = "<a href='index.php?option=com_contact&view=contact&id=" .
				$this->_item->organizerId .
				"'>" . $this->_item->organizer->name . "</a>";
		}

		if (isset($this->_item->hostId))
		{
			$db = JFactory::getDbo();
			$conQuery = $db->getQuery(true);
			$conQuery->select('name')
				->from('#__contact_details')
				->where('id = ' . $this->_item->hostId);
			$db->setQuery($conQuery);
			$host = $db->loadResult();
			$this->_item->hostLink = "<a href='index.php?option=com_contact&view=contact&id=" .
				$this->_item->hostId .
				"'>" . $host . "</a>";
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('title')
			->from('#__categories')
			->where('id = ' . $this->_item->catid);

		$db->setQuery($query);

		$this->_item->cat_title = $db->loadResult();

		if (empty($this->_item->cat_title))
		{
			$this->_item->cat_title = $this->_item->catid;
		}

		return $this->_item;
	}

	/**
	 * @param   int $eventId    The id of the event to update
	 * @param   int $status     The new Status
	 *
	 * @return boolean true on success
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function changeStatus($eventId, $status)
	{
		$table = $this->getTable('Event', 'YajemTable');

		if ($eventId)
		{
			$table->load($eventId);
			$table->set('eventStatus', $status);
		}
		else
		{
			throw new Exception("No eventId");
		}

		return $table->store(true);
	}

	public function makeIcs() {
		if ($eventId = $this->getState('item.id'))
		{
			JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_yajem/models');

			$event = $this->getData($eventId);

			if ($event->locationId) {
				//get the location
				$modelLocation = JModelLegacy::getInstance('locations', 'YajemModel');
				$location = $modelLocation->getLocation( (int) $event->locationId );
			}

			//check for attendees
			$modelAttendees = JModelLegacy::getInstance('attendees', 'YajemModel');
			$attendees = $modelAttendees->getAttendees( (int) $event->id );
			if (count($attendees)>0) {
				foreach ($attendees as $attendee) {
					if (!$attendee->userId == $event->organizer->userId) {
						$kb_attendees[]='ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN="'
							. $attendee->attendee
							. '":Mailto:' . $attendee->mail;
					}
				}
			}

			if ($event->organizerId) {
				$kb_organizer = 'ORGANIZER;CN="'. $event->organizer->name .'":Mailto:' . $event->organizer->email;
			}

			if ((bool) $event->allDayEvent) {
				$kb_start       = new DateTime($event->startDate);
				$kb_start       = $kb_start->format('Ymd');

				$kb_end         = new DateTime($event->endDate);
				$kb_end         = $kb_end->format('Ymd');
			} else {
				$kb_start       = new DateTime($event->startDateTime);
				$kb_start       = $kb_start->format('Ymd') . 'T' . $kb_start->format('Hms');

				$kb_end         = new DateTime($event->endDateTime);
				$kb_end         = $kb_end->format('Ymd') . 'T' . $kb_end->format('Hms');
			}
			$kb_current_time = new DateTime(now);
			$kb_title        = html_entity_decode($event->title, ENT_COMPAT, 'UTF-8');
			$kb_location     = html_entity_decode(
				$location->street.", ".$location->postalCode." ".$location->city,
				ENT_COMPAT,
				'UTF-8');
			$kb_description  = html_entity_decode($event->description, ENT_COMPAT, 'UTF-8');
			$eol            = "\r\n";

			$kb_ics_content =
				'BEGIN:VCALENDAR' . $eol
				. 'VERSION:2.0' . $eol
				. 'CALSCALE:GREGORIAN' . $eol
				. 'PRODID:https://www.survivants-d-acre.de' . $eol
				. 'METHOD:REQUEST' . $eol
				. 'BEGIN:VTIMEZONE' . $eol
				. 'TZID:Europe/Berlin' . $eol
				. 'BEGIN:DAYLIGHT' . $eol
				. 'TZOFFSETFROM:+0100' . $eol
				. 'TZOFFSETTO:+0200' . $eol
				. 'TZNAME:CEST' . $eol
				. 'DTSTART:19700329T020000' . $eol
				. 'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=-1SU;BYMONTH=3' . $eol
				. 'END:DAYLIGHT' . $eol
				. 'BEGIN:STANDARD' . $eol
				. 'TZOFFSETFROM:+0200' . $eol
				. 'TZOFFSETTO:+0100' . $eol
				. 'TZNAME:CET' . $eol
				. 'DTSTART:19701025T030000' . $eol
				. 'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=-1SU;BYMONTH=10' . $eol
				. 'END:STANDARD' . $eol
				. 'END:VTIMEZONE' . $eol
				. 'BEGIN:VEVENT' . $eol
				. 'UID:{UID}' . $eol
				. 'SEQUENCE:{Sequence}' . $eol
				. 'STATUS:CONFIRMED' . $eol
				. $kb_organizer . $eol;
			/* TODO Man könnte später auch auf Rückmeldung durch Kalender reagieren.
			if (count($kb_attendees)>0) {
				foreach ($kb_attendees as $kbAttendee)
				{
					$kb_ics_content = $kb_ics_content . $kbAttendee . $eol;
				}
			}*/
			$kb_ics_content = $kb_ics_content
				. 'CLASS:PUBLIC' . $eol
				. 'SUMMARY:' . $kb_title . $eol
				. 'DTSTART;TZID=Europe/Berlin:' . $kb_start . $eol
				. 'DTEND;TZID=Europe/Berlin:' . $kb_end . $eol
				. 'DTSTAMP:' . $kb_current_time->format('Ymd') . 'T' . $kb_current_time->format('Hms') . $eol
				. 'LAST-MODIFIED:' . $kb_current_time->format('Ymd') . 'T' . $kb_current_time->format('Hms') . $eol
				. 'DESCRIPTION:' . $kb_description . $eol
				. 'LOCATION:' . $kb_location . $eol
				. 'END:VEVENT' . $eol
				. 'END:VCALENDAR'
			;

			return $kb_ics_content;
		}
		return false;
	}
}
