<?php
/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

defined('_JEXEC') or die();

use Sda\Component\Sdajem\Site\Enums\AttendingStatusEnum;

/**
 * @since      1.0.0
 * @package     Sda\Component\Sdajem\Site\Model
 *
 * @property  int                 attendingId
 * @property  int                 event_id
 * @property  int                 users_user_id
 * @property  AttendingStatusEnum status
 * @property  array               profile
 * @property  array               userData
 */
class EventAttendeeModel extends UserModel
{
	public function __construct($data)
	{
		if (isset($data->users_user_id)) {
			parent::__construct($data->users_user_id);
			$this->users_user_id = $data->users_user_id;
		}
		$this->event_id = $data->event_id;
		$this->attendingId = $data->id;
		$this->status = AttendingStatusEnum::tryFrom($data->status);
	}
}