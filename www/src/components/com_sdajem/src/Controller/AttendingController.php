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
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Model\AttendingModel;

class AttendingController extends \Joomla\CMS\MVC\Controller\FormController
{
	protected $view_item = 'event';

	public function getModel($name = 'Attendingform', $prefix = '', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, ['ignore_request' => false]);
	}

	public function signup($eventId = null, $userId = null)
	{
		$this->input->set('id', $this->input->get('attendingId'));
		/* @var \Sda\Component\Sdajem\Site\Enums\EventStatusEnum $newStatus */
		if ($this->input->get('newAttendeeStatus'))
		{
			$newStatus = ($this->input->get('newAttendeeStatus'));
		}

		$data = array(
			'id' => $this->input->get('attendingId'),
			'event_id' => $this->input->get('event_id'),
			'users_user_id' => Factory::getApplication()->getIdentity()->id,
			'status' => $newStatus
		);
		$this->input->post->set('jform', $data);

		$this->save();
	}
}