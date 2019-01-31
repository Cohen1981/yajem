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
		$paramsArray = $params->toArray();

		if (isset($paramsArray['item_id']))
		{
			$this->setState('item.id', $paramsArray['item_id']);
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

	/**
	 * Generates and returns the ics file for an event.
	 *
	 * @return null|string
	 *
	 * @since 1.1.0
	 * @throws Exception
	 */
	public function makeIcs()
	{
		if ($eventId = $this->getState('item.id'))
		{
			JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_yajem/models');

			$event = $this->getData($eventId);

			if ($event->locationId)
			{
				// Get the location
				$modelLocation = JModelLegacy::getInstance('locations', 'YajemModel');
				$location = $modelLocation->getLocation((int) $event->locationId);
			}

			// Check for attendees
			$modelAttendees = JModelLegacy::getInstance('attendees', 'YajemModel');
			$attendees = $modelAttendees->getAttendees((int) $event->id);

			if (count($attendees) > 0)
			{
				foreach ($attendees as $attendee)
				{
					if (!$attendee->userId == $event->organizerId)
					{
						$kbAttendees[] = 'ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN="'
							. $attendee->attendee
							. '":Mailto:' . $attendee->mail;
					}
				}
			}

			if ($event->organizerId)
			{
				$organizer = YajemUserHelper::getUser($event->organizerId);
				$kbOrganizer = 'ORGANIZER;CN="' . $event->organizer->name . '":Mailto:' . $organizer->email;
			}

			if ((bool) $event->allDayEvent)
			{
				$kbStart       = new DateTime($event->startDate);
				$kbStart       = $kbStart->format('Ymd');

				$kbEnd         = new DateTime($event->endDate);
				$kbEnd         = $kbEnd->format('Ymd');
			}
			else
			{
				$kbStart       = new DateTime($event->startDateTime);
				$kbStart       = $kbStart->format('Ymd') . 'T' . $kbStart->format('Hms');

				$kbEnd         = new DateTime($event->endDateTime);
				$kbEnd         = $kbEnd->format('Ymd') . 'T' . $kbEnd->format('Hms');
			}

			$kbCurrentTime = new DateTime(now);
			$kbTitle        = html_entity_decode($event->title, ENT_COMPAT, 'UTF-8');
			$kbLocation     = html_entity_decode(
				$location->street . ", " . $location->postalCode . " " . $location->city,
				ENT_COMPAT,
				'UTF-8'
			);
			$kbDescription  = html_entity_decode($event->description, ENT_COMPAT, 'UTF-8');
			$eol            = "\r\n";

			$kbIcsContent
				= 'BEGIN:VCALENDAR' . $eol
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
				. $kbOrganizer . $eol;

			/*
			TODO Man könnte später auch auf Rückmeldung durch Kalender reagieren.
			if (count($kbAttendees)>0) {
				foreach ($kbAttendees as $kbAttendee)
				{
					$kbIcsContent = $kbIcsContent . $kbAttendee . $eol;
				}
			}
			*/
			$kbIcsContent = $kbIcsContent
				. 'CLASS:PUBLIC' . $eol
				. 'SUMMARY:' . $kbTitle . $eol
				. 'DTSTART;TZID=Europe/Berlin:' . $kbStart . $eol
				. 'DTEND;TZID=Europe/Berlin:' . $kbEnd . $eol
				. 'DTSTAMP:' . $kbCurrentTime->format('Ymd') . 'T' . $kbCurrentTime->format('Hms') . $eol
				. 'LAST-MODIFIED:' . $kbCurrentTime->format('Ymd') . 'T' . $kbCurrentTime->format('Hms') . $eol
				. 'DESCRIPTION:' . $kbDescription . $eol
				. 'LOCATION:' . $kbLocation . $eol
				. 'END:VEVENT' . $eol
				. 'END:VCALENDAR';

			return $kbIcsContent;
		}

		return false;
	}
}
