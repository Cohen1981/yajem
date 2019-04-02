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
use ParagonIE\Sodium\File;
use Sda\Jem\Admin\Model\Attendees;

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
 * @property   int			$sdajem_categorie_id
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
		}
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

		if ($this->registerUntil)
		{
			$regUntil = new Date($this->registerUntil);
			$currentDate = new Date;
			$regPossible = ($currentDate <= $regUntil) ? true : false;
		}

		return $regPossible;
	}
}
