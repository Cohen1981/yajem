<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

defined('_JEXEC') or die();

use Sda\Component\Sdajem\Site\Enums\IntAttStatusEnum;

/**
 * @since      1.0.0
 * @package     Sda\Component\Sdajem\Site\Model
 *
 */
class EventInterestModel extends UserModel
{
	public int $attendingId;
	public int $event_id;
	protected int $users_user_id;
	public IntAttStatusEnum $status;
	public string $comment;
	public ?array $profile;
	public ?array $userData;

	public function __construct($data)
	{
		if (isset($data->users_user_id)) {
			parent::__construct($data->users_user_id);
			$this->users_user_id = $data->users_user_id;
		}
		$this->event_id = $data->event_id;
		$this->attendingId = $data->id;
		$this->status = IntAttStatusEnum::tryFrom($data->status);
		$this->comment = $data->comment;
	}
}