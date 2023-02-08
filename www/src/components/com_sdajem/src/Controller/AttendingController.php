<?php
/**
 * @package     Sda\Component\Sdajem\Site\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Controller;

use Joomla\CMS\Factory;
use Sda\Component\Sdajem\Site\Enums\AttendingStatusEnum;
use Sda\Component\Sdajem\Site\Model\AttendingModel;

class AttendingController extends \Joomla\CMS\MVC\Controller\FormController
{
	protected $view_item = 'event';

	public function getModel($name = 'Attendingform', $prefix = '', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, ['ignore_request' => false]);
	}

	public function attend($eventId = null, $userId = null)
	{
		$this->input->set('id', $this->input->get('attendingId'));

		$data = array(
			'id' => $this->input->get('attendingId'),
			'event_id' => $this->input->get('event_id'),
			'users_user_id' => Factory::getApplication()->getIdentity()->id,
			'status' => AttendingStatusEnum::ATTENDING->value
		);
		$this->input->post->set('jform', $data);

		$this->save();
	}

	public function unattend($eventId = null, $userId = null)
	{
		$this->input->set('id', $this->input->get('attendingId'));

		$data = array(
			'id' => $this->input->get('attendingId'),
			'event_id' => $this->input->get('event_id'),
			'users_user_id' => Factory::getApplication()->getIdentity()->id,
			'status' => AttendingStatusEnum::NOT_ATTENDING->value
		);
		$this->input->post->set('jform', $data);

		$this->save();
	}
}