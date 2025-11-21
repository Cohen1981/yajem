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

use Joomla\CMS\User\User;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Enums\IntAttStatusEnum;

/**
 * @since      1.0.0
 * @package     Sda\Component\Sdajem\Site\Model
 *
 */
class EventAttendeeModel extends UserModel
{
	public ?int $attendingId;
	public ?int $event_id;
	protected ?int $users_user_id;
	public ?IntAttStatusEnum $status;
	public ?array $profile;
	public ?array $userData;
	public ?User $user = null;
	public EventStatusEnum $event_status = EventStatusEnum::PLANING;

	public function __construct(\stdClass $data)
	{
		if (isset($data->users_user_id)) {
			parent::__construct($data->users_user_id);
			$this->users_user_id = $data->users_user_id;
		}
		$this->event_id = $data->event_id;
		$this->attendingId = $data->id;
		$this->status = IntAttStatusEnum::tryFrom($data->status);
		$this->event_status = EventStatusEnum::PLANING::tryFrom($data->event_status);
	}
}