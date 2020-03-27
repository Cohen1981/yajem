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
 * @property   array        $commentReadBy
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
	 * @param Container $container The Container
	 * @param array $config The Configuration
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
	 * @param $value
	 * @return array
	 * @since 1.0.0
	 */
	protected function getCommentReadByAttribute($value)
	{
		return (array)json_decode($value);
	}

	/**
	 * @param $value
	 * @return false|string|null
	 * @since 1.0.0
	 */
	protected function setCommentReadByAttribute($value)
	{
		// Array passes the isJson check, so we need a seperate check.
		if (is_array($value)) {
			return json_encode($value);
		} elseif ($this->isJson($value)) {
			return $value;
		} else {
			return null;
		}
	}

	/**
	 * @param $string
	 * @return bool
	 * @since 1.0.0
	 */
	private function isJson($string)
	{
		json_decode($string);

		return (json_last_error() == JSON_ERROR_NONE);
	}

	/**
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeSave()
	{
		if ($this->timestamp instanceof \DateTime) {
			$this->timestamp = $this->timestamp->toSql(false, $this->getDbo());
		}
	}

	/**
	 * @param int $userId
	 * @return boolean true if read
	 * @since 1.0.0
	 */
	public function isUnreadComment(int $userId): bool
	{
		$returnVal = true;

		if ($this->commentReadBy) {
			$returnVal = (!in_array($userId, $this->commentReadBy));
		}

		return $returnVal;
	}
}