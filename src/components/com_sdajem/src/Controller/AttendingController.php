<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @package     Sda\Component\Sdajem\Site\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Controller;

defined('_JEXEC') or die();

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Uri\Uri;
use Sda\Component\Sdajem\Administrator\Helper\AttendingHelper;
use Sda\Component\Sdajem\Site\Enums\IntAttStatusEnum;

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

	private function getPks()
	{
		$pks = [];

		if ($this->input->get('event_id')) {
			$pks[0] = $this->input->get('event_id');
		} else if ($eventId !== null)
		{
			$pks[0] = $eventId;
		} else {
			$pks = $this->input->get('cid');
		}
		return $pks;
	}
	/**
	 * @param   null  $key
	 * @param   null  $urlVar
	 *
	 * @return bool
	 *
	 * @since 1.0.1
	 * @throws Exception
	 */
	public function save($key = null, $urlVar = null)
	{
		// we are not in event context and have to check if attending exists
		if($this->doTask === 'save')
		{
			$input = $this->input->get('jform');

			if (!$input['users_user_id']) {
				$input['users_user_id'] = Factory::getApplication()->getIdentity()->id;
			}

			$data = AttendingHelper::getAttendingStatusToEvent($input['users_user_id'], $input['event_id']);
			// attending exists so we set the id for updating the record
			if ($data)
			{
				$this->input->set('id', $data->id);
			}
		}

		return parent::save($key, $urlVar);
	}

	/**
	 * @param null $eventId
	 * @param null $userId
	 *
	 * @throws Exception
	 * @since 1.0.1
	 */
	public function attend($eventId = null, $userId = null)
	{
		//$this->option = 'core.manage.attending';
		$pks = $this->getPks();

		if ($this->input->get('event_id')) {
			$pks[0] = $this->input->get('event_id');
		} else if ($eventId !== null)
		{
			$pks[0] = $eventId;
		} else {
			$pks = $this->input->get('cid');
		}

		if (count($pks) >= 0) {

			if ($userId !== null) {
				$currUser = $userId;
			} else
			{
				$currUser = Factory::getApplication()->getIdentity();
			}

			foreach ($pks as $id)
			{
				$attending = AttendingHelper::getAttendingStatusToEvent($currUser->id, $id);

				$this->input->set('id', $attending->id);

				$data = array(
					'id'            => $attending->id,
					'event_id'      => $id,
					'users_user_id' => $currUser->id,
					'status'        => IntAttStatusEnum::POSITIVE->value
				);

				$this->input->post->set('jform', $data);

				$this->save();
			}
		}
	}

	/**
	 * @param null $eventId
	 * @param null $userId
	 *
	 * @throws Exception
	 * @since 1.0.1
	 */
	public function unattend($eventId = null, $userId = null)
	{
		//$this->option = 'core.manage.attending';
		$pks = $this->getPks();

		if (count($pks) >= 0) {

			if ($userId !== null) {
				$currUser = $userId;
			} else
			{
				$currUser = Factory::getApplication()->getIdentity();
			}

			foreach ($pks as $id)
			{
				$attending = AttendingHelper::getAttendingStatusToEvent($currUser->id, $id);

				$this->input->set('id', $attending->id);

				$data = array(
					'id'            => $attending->id,
					'event_id'      => $id,
					'users_user_id' => $currUser->id,
					'status'        => IntAttStatusEnum::NEGATIVE->value
				);

				$this->input->post->set('jform', $data);

				$this->save();
			}
		}
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