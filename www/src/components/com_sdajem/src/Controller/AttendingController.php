<?php
/**
 * @package     Sda\Component\Sdajem\Site\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Controller;

use Sda\Component\Sdajem\Site\Enums\EventStatus;
use Sda\Component\Sdajem\Site\Model\AttendingModel;

class AttendingController extends \Joomla\CMS\MVC\Controller\FormController
{
	public function getModel($name = 'Attending', $prefix = '', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, ['ignore_request' => false]);
	}

	public function signup($eventId = null, $userId = null)
	{
		/* @var AttendingModel $attending */
		$attending = new AttendingModel();

		if (!$eventId) {
			$attending->event_id = $this->input->get('eventId');
		}
		if (!$userId) {
			$attending->users_user_id = $this->input->get('userId');
		}

		$attending->status = EventStatus::ATTENDING->value;

		$this->save();
	}
}