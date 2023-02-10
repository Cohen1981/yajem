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
use Joomla\CMS\MVC\Controller\FormController;
use Sda\Component\Sdajem\Site\Enums\AttendingStatusEnum;
use Sda\Component\Sdajem\Site\Model\AttendingModel;

class AttendingController extends FormController
{
	protected $view_item = 'event';

	public function getModel($name = 'Attendingform', $prefix = '', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, ['ignore_request' => false]);
	}

	public function attend($eventId = null, $userId = null)
	{
		$this->input->set('id', $this->input->get('attendingId'));
		$this->option = 'core.manage.attending';

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

	/**
	 * Method to check if you can add a new record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = [])
	{
		return !$this->app->getIdentity()->guest;
	}

	/**
	 * Method to check if you can edit an existing record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key; default is id.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = [], $key = 'id')
	{
		return !$this->app->getIdentity()->guest;
	}
}