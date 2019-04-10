<?php
/**
 * @package     Sda\Jem\Admin\Model
 * @subpackage
 *
 * @copyright   Alexander Bahlo
 * @license     GPL2
*/

namespace Sda\Jem\Admin\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use FOF30\Date\Date;
use FOF30\Utils\Collection;
use Joomla\CMS\Component\ComponentHelper;

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\Event
 *
 * Fields:
 *
 * @property   int			$sdajem_event_id
 * @property   int			$sdajem_category_id
 * @property   string		$title
 * @property   string		$slug
 * @property   string		$description
 * @property   string		$url
 * @property   string		$image
 * @property   int			$sdajem_location_id
 * @property   int			$hostId
 * @property   int			$organizerId
 * @property   Date			$startDateTime
 * @property   Date			$endDateTime
 * @property   int			$allDayEvent
 * @property   int			$useRegistration
 * @property   Date			$registerUntil
 * @property   int			$registrationLimit
 * @property   int			$useWaitingList
 * @property   int			$eventStatus
 * @property   int			$access
 * @property   int			$enabled
 * @property   int			$locked_by
 * @property   Date			$locked_on
 * @property   int			$hits
 * @property   int			$ordering
 * @property   Date			$created_on
 * @property   int			$created_by
 * @property   Date			$modified_on
 * @property   int			$modified_by
 *
 * Relations:
 *
 * @property  Contact       $host
 * @property  User          $organizer
 * @property  Category      $category
 * @property  Location      $location
 * @property  Collection    $comments
 * @property  Collection    $attendees
 * @property  Mailing       $subscriptions
 */
class Event extends DataModel
{
	/**
	 * Event constructor.
	 *
	 * @param   Container $container The Container
	 * @param   array     $config    The Configuration
	 *
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		$config['behaviours'] = array('Filters', 'Access');
		parent::__construct($container, $config);

		$this->hasOne('host', 'Contact', 'hostId', 'id');
		$this->hasOne('organizer', 'User', 'organizerId', 'id');
		$this->hasOne('category', 'Category', 'sdajem_category_id', 'sdajem_category_id');
		$this->hasOne('location', 'Location', 'sdajem_location_id', 'sdajem_location_id');
		$this->hasMany('comments', 'Comment');
		$this->hasMany('attendees', 'Attendee', 'sdajem_event_id', 'sdajem_event_id');
		$this->hasMany('subscriptions', 'Mailing', 'sdajem_event_id', 'sdajem_event_id');
	}

	/**
	 * Returns the number of attending users.
	 *
	 * @return integer Number of attending Users. "Status = 1"
	 *
	 * @since 0.0.1
	 */
	public function getAttendingCount() : int
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('count(*)')
			->from('#__sdajem_attendees')
			->where(array('sdajem_event_id=' . $this->sdajem_event_id, 'status=1'));
		$db->setQuery($query);
		$count = $db->loadResult();

		return $count;
	}

	/**
	 *
	 * @return integer
	 *
	 * @since 0.0.1
	 */
	public function getRequiredSpaceForEvent() : int
	{
		$spaceRequired = 0;

		if ($this->attendees)
		{
			/** @var Attendee $attendee */
			foreach ($this->attendees as $attendee)
			{
				$spaceRequired = $spaceRequired + $attendee->getSpaceReuirementForEvent();
			}
		}

		return $spaceRequired;
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	protected function onBeforeSave()
	{
		$input = $this->input->post->getArray();

		if ($input['task'] == "save")
		{
			if ($input['registerUntil'] == "")
			{
				$this->registerUntil = null;
			}

			if ($input['hostId'] == "")
			{
				$this->hostId = null;
			}

			if ($input['organizerId'] == "")
			{
				$this->organizerId = null;
			}

			if ($input['url'] == "")
			{
				$this->url = null;
			}

			if ($input['image'] == "")
			{
				$this->image = null;
			}

			if ($input['sdajem_location_id'] == "")
			{
				$this->sdajem_location_id = null;
			}

			if ($input['registrationLimit'] == "" || $input['registrationLimit'] == 0)
			{
				$this->registrationLimit = null;
			}
		}
	}

	/**
	 * Make sure to store a valid URL
	 *
	 * @param   string $value The input vaue
	 *
	 * @return string
	 *
	 * @since 0.0.1
	 */
	protected function setUrlAttribute($value)
	{
		if (substr($value, 0, 4) != "http" && $value != "")
		{
			$value = "http://" . $value;
		}

		return $value;
	}

	/**
	 * Returns the formatted start date considering the allDayEvent State
	 *
	 * @return string
	 *
	 * @since 0.0.1
	 */
	public function getFormatedStartDate() : string
	{
		if ((bool) $this->allDayEvent)
		{
			return $this->startDateTime->format('d.m.Y');
		}
		else
		{
			return $this->startDateTime->format('d.m.Y H:i');
		}
	}

	/**
	 * Returns the formatted end date considering the allDayEvent State
	 *
	 * @return string
	 *
	 * @since 0.0.1
	 */
	public function getFormatedEndDate() : string
	{
		if ((bool) $this->allDayEvent)
		{
			return $this->endDateTime->format('d.m.Y');
		}
		else
		{
			return $this->endDateTime->format('d.m.Y H:i');
		}
	}

	/**
	 * @param   string $value The date and time as string
	 *
	 * @return Date
	 *
	 * @since 0.0.1
	 */
	protected function getStartDateTimeAttribute($value)
	{
		// Make sure it's not a Date already
		if (is_object($value) && ($value instanceof Date))
		{
			return $value;
		}

		// Return the data transformed to a Date object
		return new Date($value);
	}

	/**
	 * @param   Date $value The date and time as Date
	 *
	 * @return string
	 *
	 * @since 0.0.1
	 */
	protected function setStartDateTimeAttribute($value)
	{
		if ($value instanceof Date)
		{
			return $value->toSql();
		}

		return $value;
	}

	/**
	 * @param   Date $value The date and time as Date
	 *
	 * @return string
	 *
	 * @since 0.0.1
	 */
	protected function setEndDateTimeAttribute($value)
	{
		if ($value instanceof Date)
		{
			return $value->toSql();
		}

		return $value;
	}

	/**
	 * @param   string $value The date and time as string
	 *
	 * @return Date
	 *
	 * @since 0.0.1
	 */
	protected function getEndDateTimeAttribute($value)
	{
		// Make sure it's not a Date already
		if (is_object($value) && ($value instanceof Date))
		{
			return $value;
		}

		// Return the data transformed to a Date object
		return new Date($value);
	}

	/**
	 * @param   Date $value The date and time as Date
	 *
	 * @return string
	 *
	 * @since 0.1.1
	 */
	protected function setRegisterUntilAttribute($value)
	{
		if ($value instanceof Date)
		{
			return $value->toSql();
		}

		return $value;
	}

	/**
	 * @param   string $value The date and time as string
	 *
	 * @return Date
	 *
	 * @since 0.1.1
	 */
	protected function getRegisterUntilAttribute($value)
	{
		// Make sure it's not a Date already
		if ((is_object($value) && ($value instanceof Date)) || $value == null)
		{
			return $value;
		}

		// Return the data transformed to a Date object
		return new Date($value);
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	protected function onBeforeDelete()
	{
		if ($this->attendees)
		{
			/** @var Attendee $attendee */
			foreach ($this->attendees as $attendee)
			{
				$attendee->forceDelete();
			}
		}
	}

	/**
	 * Checks for useRegistration and waiting list status as well as register until.
	 *
	 * @return boolean true if User can attend
	 *
	 * @since 0.0.1
	 */
	public function isRegistrationPossible() : bool
	{
		$regPossible = false;

		if ((bool) $this->useRegistration && (!(bool) $this->useWaitingList || $this->getAttendingCount() < $this->registrationLimit ))
		{
			$regPossible = true;
		}

		if ($this->registerUntil != '0000-00-00')
		{
			$regUntil = new Date($this->registerUntil);
			$currentDate = new Date;
			$regPossible = ($currentDate <= $regUntil) ? true : false;
		}

		return $regPossible;
	}

	/**
	 * Generates and returns the ics file for an event.
	 *
	 * @return null|string
	 *
	 * @since 0.1.3
	 */
	public function makeIcs()
	{
		/** @var Attendee $attendee */
		foreach ($this->attendees as $attendee)
		{
			if ($attendee->status == 1)
			{
				$kbAttendees[] = 'ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN="'
					. $attendee->user->name
					. '":Mailto:' . $attendee->user->email;
			}
		}

		if ($this->organizerId)
		{
				$kbOrganizer = 'ORGANIZER;CN="' . $this->organizer->name . '":Mailto:' . $this->organizer->email;
		}

		if ((bool) $this->allDayEvent)
		{
			$kbStart       = $this->startDateTime->format('Ymd');
			$kbEnd         = $this->endDateTime->format('Ymd');
		}
		else
		{
			$kbStart       = $this->startDateTime->format('Ymd') . 'T' . $this->startDateTime->format('Hms');
			$kbEnd         = $this->endDateTime->format('Ymd') . 'T' . $this->endDateTime->format('Hms');
		}

		$kbCurrentTime = new Date;
		$kbTitle        = html_entity_decode($this->title, ENT_COMPAT, 'UTF-8');

		if ($this->location)
		{
			$kbLocation = html_entity_decode(
				$this->location->street . ", " . $this->location->postalCode . " " . $this->location->city,
				ENT_COMPAT,
				'UTF-8'
			);
		}

		$kbDescription  = html_entity_decode($this->description, ENT_COMPAT, 'UTF-8');
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

}
