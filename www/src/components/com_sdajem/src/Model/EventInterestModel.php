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

use Sda\Component\Sdajem\Site\Enums\InterestStatusEnum;

/**
 * @since      1.0.0
 * @package     Sda\Component\Sdajem\Site\Model
 *
 * @property  int                 attendingId
 * @property  int                 event_id
 * @property  int                 users_user_id
 * @property  InterestStatusEnum  status
 * @property  array               profile
 * @property  array               userData
 */
class EventInterestModel extends UserModel
{
	public function __construct($data)
	{
		if (isset($data->users_user_id)) {
			parent::__construct($data->users_user_id);
			$this->users_user_id = $data->users_user_id;
		}
		$this->event_id = $data->event_id;
		$this->attendingId = $data->id;
		$this->status = InterestStatusEnum::tryFrom($data->status);
	}
}