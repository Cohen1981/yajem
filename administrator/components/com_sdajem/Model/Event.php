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
		parent::__construct($container, $config);
		$this->addBehaviour('Filters');
		$this->hasOne('host', 'Contact', 'hostId', 'id');
		$this->hasOne('organizer', 'User', 'organizerId', 'id');
		$this->hasOne('category', 'Category');
		$this->hasOne('location', 'Location', 'sdajem_location_id', 'sdajem_location_id');
		$this->hasMany('comments', 'Comment');
		$this->hasMany('attendees', 'Attendee');
	}
}
