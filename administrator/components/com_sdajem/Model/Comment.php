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

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\Comment
 *
 * Fields:
 *
 * @property   int			$sdajem_comment_id
 * @property   int			$users_user_id
 * @property   int			$sdajem_event_id
 * @property   string		$comment
 * @property   Date			$timestamp
 *
 * Relations:
 *
 * @property  User          $user
 */
class Comment extends DataModel
{
	/**
	 * Comment constructor.
	 *
	 * @param   Container $container The Container
	 * @param   array     $config    The Configuration
	 *
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
		$this->hasOne('user', 'User', 'users_user_id', 'id');
		$this->hasOne('event', 'Event', 'sdajem_event_id', 'sdajem_event_id');
		$this->orderBy('timestamp', 'DESC');
	}

	/**
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeSave()
	{
		$this->timestamp = $this->timestamp->toSql(false, $this->getDbo());
	}
}
