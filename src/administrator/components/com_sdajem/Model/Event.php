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
use FOF30\Date\Date;
use FOF30\Utils\Collection;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Sda\Profiles\Admin\Model\Profile;
use Sda\Model\SdaProtoModel;

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
 * @property   int          $useFittings
 * @property   int          $fittingProfile
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
 * @property   array        $svg
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
 * @property  Profile       $fProfile
 */
class Event extends SdaProtoModel
{
	/**
	 * @var array
	 * @since 0.4.1
	 */
	public $fieldErrors;
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
		$config['aliasFields'] = array('startDate' => 'startDateTime', 'endDate' => 'endDateTime');
		parent::__construct($container, $config);

		$this->hasOne('host', 'Contact', 'hostId', 'id');
		$this->hasOne('organizer', 'User', 'organizerId', 'id');
		$this->hasOne('category', 'Category', 'sdajem_category_id', 'sdajem_category_id');
		$this->hasOne('location', 'Location', 'sdajem_location_id', 'sdajem_location_id');
		$this->hasMany('comments', 'Comment');
		$this->hasMany('attendees', 'Attendee', 'sdajem_event_id', 'sdajem_event_id');
		$this->hasMany('subscriptions', 'Mailing', 'sdajem_event_id', 'sdajem_event_id');

		if (ComponentHelper::isEnabled('com_sdaprofiles'))
		{
			$this->hasOne('fProfile', 'Profile@com_sdaprofiles', 'fittingProfile', 'sdaprofiles_profile_id');
		}
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
			->where(array('sdajem_event_id=' . $this->sdajem_event_id, 'status=1', 'users_user_id is not null'));
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

		if ($input['task'] == "save" || $input['task'] == 'apply')
		{
			if ($input['registerUntil'] == "" || $input['registerUntil'] == "0000-00-00 00:00:00")
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

			if ($input['useRegistration'] == 0)
			{
				$this->registerUntil = null;
				$this->registrationLimit = 0;
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
		if ($this->startDateTime != null)
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
		else
		{
			return '';
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
		if ($this->endDateTime != null)
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
		else
		{
			return '';
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
		return $this->getDateValue($value);
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
		if ($value == '')
		{
			return $this->getDateValue($this->startDateTime);
		}

		return $this->getDateValue($value);
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
	 * @param   mixed $value The date and time as string
	 *
	 * @return Date | null
	 *
	 * @since 0.1.1
	 */
	protected function getRegisterUntilAttribute($value)
	{
		return $this->getDateValue($value);
	}

	/**
	 * @param   string $value The Value to set
	 *
	 * @return string|null
	 *
	 * @since  0.3.4
	 */
	protected function setFittingProfileAttribute($value)
	{
		if ($value == '')
		{
			return null;
		}
		else
		{
			return $value;
		}
	}

	/**
	 * @param   mixed $value The date and time as string
	 *
	 * @return Date | null
	 *
	 * @since 0.1.1
	 */
	private function getDateValue($value)
	{
		if ($value == null || $value == "0000-00-00")
		{
			return null;
		}

		// Make sure it's not a Date already
		if (is_object($value) && ($value instanceof Date))
		{
			return $value;
		}

		// Return the data transformed to a Date object
		return new Date($value);
	}

	/**
	 * Enforcing data sanity
	 *
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
		if ($this->comments)
		{
			/** @var Comment $comment */
			foreach ($this->comments as $comment)
			{
				$comment->forceDelete();
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
			$regPossible = ($currentDate->format('Ymd') <= $regUntil->format('Ymd')) ? true : false;
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
	public function getIcs()
	{
		$kbAttendees = array();
		/** @var Attendee $attendee */
		foreach ($this->attendees as $attendee)
		{
			if ($attendee->status == 1 && $attendee->users_user_id)
			{
				$attendeeString = 'CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;X-NUM-GUESTS=0;CN="'
					. $attendee->user->name
					. '":mailto:' . $attendee->user->email;

				/*
				$attendeeString = 'ATTENDEE;CN="' . $attendee->user->name . "';CUTYPE=INDIVIDUAL;PARTSTAT=NEEDS-ACTION;
					ROLE=REQ-PARTICIPANT;RSVP=TRUE:mailto:" . $attendee->user->email;
				*/
				array_push($kbAttendees, $attendeeString);
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
			$kbStart       = $this->startDateTime->format('Ymd') . 'T' . $this->startDateTime->format('Hms') . 'Z';
			$kbEnd         = $this->endDateTime->format('Ymd') . 'T' . $this->endDateTime->format('Hms') . 'Z';
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

		$kbCreated = new Date($this->created_on);
		$kbModified = new Date($this->modified_on);

		$kbDescription  = html_entity_decode($this->description, ENT_COMPAT, 'UTF-8');
		$eol            = "\r\n";
		$kbIcsContent
			= 'BEGIN:VCALENDAR' . $eol
			. 'PRODID:https://www.survivants-d-acre.de' . $eol
			. 'VERSION:2.0' . $eol
			. 'CALSCALE:GREGORIAN' . $eol
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
			. 'END:VTIMEZONE' . $eol;

		$kbIcsContent = $kbIcsContent
			. 'BEGIN:VEVENT' . $eol
			. 'DTSTART;TZID=Europe/Berlin:' . $kbStart . $eol
			. 'DTEND;TZID=Europe/Berlin:' . $kbEnd . $eol
			. 'DTSTAMP:' . $kbCurrentTime->format('Ymd') . 'T' . $kbCurrentTime->format('Hms') . 'Z' . $eol
			. $kbOrganizer . $eol
			. 'UID:Event_' . $this->sdajem_event_id . '@survivants-d-acre.com' . $eol;

		if (count($kbAttendees) > 0)
		{
			foreach ($kbAttendees as $kbAttendee)
			{
				$kbIcsContent = $kbIcsContent . $kbAttendee . $eol;
			}
		}

		$kbIcsContent = $kbIcsContent
			. 'CREATED:' . $kbCreated->format('Ymd') . 'T' . $kbCreated->format('Hms') . 'Z' . $eol
			. 'DESCRIPTION:' . $kbDescription . $eol
			. 'LAST-MODIFIED:' . $kbCurrentTime->format('Ymd') . 'T' . $kbCurrentTime->format('Hms') . $eol
			. 'LOCATION:' . $kbLocation . $eol
			. 'SEQUENCE:0' . $eol
			. 'STATUS:CONFIRMED' . $eol
			. 'SUMMARY:' . $kbTitle . $eol
			. 'TRANSP:OPAQUE' . $eol
			. 'END:VEVENT' . $eol
			. 'END:VCALENDAR';

		return $kbIcsContent;
	}

	/**
	 *
	 * @return boolean true on no Error
	 *
	 * @since 0.4.1
	 */
	public function checkFields()
	{
		$errors = array();
		$returnVal = true;

		try
		{
			if ($this->input->get('title') == '')
			{
				array_push($errors, 'title');
				Factory::getApplication()->enqueueMessage(Text::_('COM_SDAJEM_TITLE_MISSING'), 'error');
				$returnVal = false;
			}

			if ($this->input->get('startDateTime') == '')
			{
				array_push($errors, 'startDateTime');
				Factory::getApplication()->enqueueMessage(Text::_('COM_SDAJEM_STARTDATE_MISSING'), 'error');
				$returnVal = false;
			}

			if ($this->input->get('endDateTime') == '' && $this->input->get('allDayEvent') != 1)
			{
				array_push($errors, 'endDateTime');
				Factory::getApplication()->enqueueMessage(Text::_('COM_SDAJEM_ENDDATE_MISSING'), 'error');
				$returnVal = false;
			}
		}
		catch (\Exception $e)
		{
		}

		try
		{
			Factory::getApplication()->setUserState('fieldErrors', $errors);
		}
		catch (\Exception $e)
		{
		}

		return $returnVal;
	}

	/**
	 * @param   mixed $value The svg
	 *
	 * @return string
	 *
	 * @since 0.4.2
	 */
	protected function setSvgAttribute($value)
	{
		// Array passes the isJson check, so we need a seperate check.
		if (is_array($value))
		{
			return json_encode($value);
		}
		elseif ($this->isJson($value))
		{
			return $value;
		}
		else
		{
			return null;
		}
	}

	/**
	 * @param   string $value The svg
	 *
	 * @return array | null
	 *
	 * @since 0.4.2
	 */
	protected function getSvgAttribute($value)
	{
		return (array) json_decode($value);
	}

	/**
	 * @param   string $string The string to check
	 *
	 * @return boolean
	 *
	 * @since 0.4.2
	 */
	private function isJson($string)
	{
		json_decode($string);

		return (json_last_error() == JSON_ERROR_NONE);
	}

	/**
	 * @param int $userId
	 * @return int
	 * @since 1.1.0
	 */
	public function hasUnreadComments (int $userId):int {
		$comCount = 0;

		/** @var Comment $comment */
		foreach ($this->comments as $comment) {
			if ($comment->isUnreadComment($userId)) {
				$comCount++;
			}
		}

		return $comCount;
	}

	/**
	 * @param string $filterString
	 * @return array
	 * @since 1.1.0
	 */
	public function getFilters(string $filterString): array
	{
		$filters = parent::getFilters($filterString);
		sort($filters);

		if ($filterString == 'startDateTime' || $filterString == 'endDateTime') {

			foreach ($filters as &$filter) {
				$filter = $this->getMonthName($this->getDateValue($filter)->month);
			}
			return array_unique($filters);
		} else {
			return $filters;
		}
	}

	/**
	 * @return string
	 * @since 1.1.0
	 */
	public function getStartMonth() {
		return $this->getMonthName($this->getDateValue($this->startDateTime)->month);
	}

	/**
	 * @param string $month
	 * @return string
	 * @since 1.1.0
	 */
	private function getMonthName(string $month):string {
		switch ($month) {
			case "01":
				$monthName = Text::_('SDAJEM_MONTH_01');
				break;
			case "02":
				$monthName = Text::_('SDAJEM_MONTH_02');
				break;
			case "03":
				$monthName = Text::_('SDAJEM_MONTH_03');
				break;
			case "04":
				$monthName = Text::_('SDAJEM_MONTH_04');
				break;
			case "05":
				$monthName = Text::_('SDAJEM_MONTH_05');
				break;
			case "06":
				$monthName = Text::_('SDAJEM_MONTH_06');
				break;
			case "07":
				$monthName = Text::_('SDAJEM_MONTH_07');
				break;
			case "08":
				$monthName = Text::_('SDAJEM_MONTH_08');
				break;
			case "09":
				$monthName = Text::_('SDAJEM_MONTH_09');
				break;
			case "10":
				$monthName = Text::_('SDAJEM_MONTH_10');
				break;
			case "11":
				$monthName = Text::_('SDAJEM_MONTH_11');
				break;
			case "12":
				$monthName = Text::_('SDAJEM_MONTH_12');
				break;
		}

		return $monthName;
	}
}
