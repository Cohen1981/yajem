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
use Joomla\CMS\Language\Text;

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\Attendee
 *
 * Fields:
 *
 * @property   int			$sdajem_attendee_id
 * @property   int			$sdajem_event_id
 * @property   int			$users_user_id
 * @property   int			$status
 *
 * Relations:
 *
 * @property  User          $user
 * @property  Event         $event
 */
class Attendee extends DataModel
{
	/**
	 * @var array
	 * @since 0.0.1
	 */
	protected $fillable = array('status', 'sdajem_event_id', 'users_user_id');

	/**
	 * Attendee constructor.
	 *
	 * @param   Container $container The Container
	 * @param   array     $config    The Configuration
	 *
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
		$this->belongsTo('event', 'Event');
		$this->hasOne('user', 'User', 'users_user_id', 'id');
	}

	/**
	 * @param   int $userId     User Id
	 * @param   int $eventId    Event Id
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function getAttendeeForEventAndUser(int $userId, int $eventId)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('sdajem_attendee_id')
			->from('#__sdajem_attendees')
			->where(array('sdajem_event_id=' . $eventId, 'users_user_id=' . $userId));
		$db->setQuery($query);
		$this->load($db->loadResult());
	}
}
