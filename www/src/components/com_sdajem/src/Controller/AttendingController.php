<?php
/**
 * @package     Sda\Component\Sdajem\Site\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Controller;

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Uri\Uri;
use Sda\Component\Sdajem\Site\Enums\AttendingStatusEnum;
use Sda\Component\Sdajem\Site\Model\AttendingModel;

/**
 * @since       1.0.0
 * @package     Sda\Component\Sdajem\Site\Controller
 *
 */
class AttendingController extends FormController
{
	protected $view_item = 'attendingform';

	public function getModel($name = 'attendingform', $prefix = '', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, ['ignore_request' => false]);
	}

	/**
	 * @param   null  $key
	 * @param   null  $urlVar
	 *
	 * @return bool
	 *
	 * @since 1.0.1
	 *        TODO austesten
	 */
	public function save($key = null, $urlVar = null)
	{
		$input = $this->input->get('jform');

		if($input)
		{
			/* @var AttendingModel $attendingModel */
			$attendingModel = $this->getModel('attending');

			$data = $attendingModel->getAttendingStatusToEvent($input['users_user_id'], $input['event_id']);
			if ($data)
			{
				$this->input->set('id', $data->id);
			}
		}

		return parent::save($key, $urlVar);
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

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string    The arguments to append to the redirect URL.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getRedirectToItemAppend($recordId = 0, $urlVar = 'id')
	{
		// Need to override the parent method completely.
		$tmpl = $this->input->get('tmpl');
		$append = '';
		// Setup redirect info.
		if ($tmpl) {
			$append .= '&tmpl=' . $tmpl;
		}
		$append .= '&layout=edit';
		$append .= '&' . $urlVar . '=' . (int) $recordId;
		$itemId = $this->input->getInt('Itemid');
		$return = $this->getReturnPage();
		if ($itemId) {
			$append .= '&Itemid=' . $itemId;
		}
		if ($return) {
			$append .= '&return=' . base64_encode($return);
		}
		return $append;
	}

	/**
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return  string    The return URL.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'base64');
		if (empty($return) || !Uri::isInternal(base64_decode($return))) {
			return Uri::base();
		}
		return base64_decode($return);
	}
}