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

use Joomla\CMS\Dispatcher\Dispatcher;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Categories\Administrator\Model\CategoryModel;
use Joomla\Utilities\ArrayHelper;
use Sda\Component\Sdajem\Site\Enums\AttendingStatusEnum;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Model\AttendingformModel;
use Sda\Component\Sdajem\Site\Model\AttendingModel;
use Sda\Component\Sdajem\Site\Model\AttendingsModel;
use Sda\Component\Sdajem\Site\Model\EventformModel;
use Sda\Component\Sdajem\Site\Model\EventModel;

class EventController extends FormController
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $view_item = 'eventform';
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getModel($name = 'eventform', $prefix = '', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, ['ignore_request' => false]);
	}
	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function allowAdd($data = [])
	{
		$user = Factory::getApplication()->getIdentity();

		return $user->authorise('core.create', 'com_sdajem');
	}
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key; default is id.
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function allowEdit($data = [], $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		if (!$recordId) {
			return false;
		}
		// Need to do a lookup from the model.
		$record     = $this->getModel()->getItem($recordId);

		$user = Factory::getApplication()->getIdentity();

		if ($user->authorise('core.edit', 'com_sdajem')) {
			return true;
		}
			// Fallback on edit.own.
		if ($user->authorise('core.edit.own', 'com_sdajem')) {
			return ($record->created_by == $user->id);
		}
		return false;
	}
	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function save($key = null, $urlVar = null)
	{
		$result = parent::save($key, $urlVar = null);

		#$this->setRedirect(Route::_($this->getReturnPage(), false));

		return $result;
	}
	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function cancel($key = null)
	{
		$result = parent::cancel($key);
		$this->setRedirect(Route::_($this->getReturnPage(), false));
		return $result;
	}

	/**
	 *
	 * @return bool
	 *
	 * @since 1.0.1
	 */
	public function delete()
	{
		$pks = $this->input->get('cid');

		$attendingFormModel = new AttendingformModel();
		$attendingsModel = new AttendingsModel();

		foreach ($pks as &$pk) {
			$attendings = $attendingsModel->getAttendingsIdToEvent($pk);
			$attResult = $attendingFormModel->delete($attendings);
		}

		$eventFormModel = new EventformModel();

		if ($attResult)
		{
			$result = $eventFormModel->delete($pks);
		}

		$this->setRedirect(Route::_($this->getReturnPage(), false));
		return $result;
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

	public function applied()
	{
		$this->setEventStatus(EventStatusEnum::APPLIED);
		$this->setRedirect(Route::_($this->getReturnPage(), false));
	}

	public function canceled()
	{
		$this->setEventStatus(EventStatusEnum::CANCELED);
		$this->setRedirect(Route::_($this->getReturnPage(), false));
	}

	public function confirmed()
	{
		$this->setEventStatus(EventStatusEnum::CONFIRMED);
		$this->setRedirect(Route::_($this->getReturnPage(), false));
	}

	protected function setEventStatus(EventStatusEnum $enum) {
		$eventId = $this->input->get('eventId');

		if ($eventId != null) {
			/* @var EventformModel $event */
			$event = $this->getModel();
			$event->updateEventStatus($eventId, $enum);
		}
	}

	public function addCategory() {
		$input = Factory::getApplication()->input;
		$app = Factory::getApplication();
		$user = $app->getIdentity();
		$extension = 'com_sdajem';
		$data = array();
		$data['title'] = $input->get('newCat', '');

		if (($user->authorise('core.create', $extension)
			|| count($user->getAuthorisedCategories($extension, 'core.create')))
			&& $data['title']!='')
		{
			$data['id'] = '';
			$data['alias']     = $input->get('categoryalias', '');
			$data['extension'] = 'com_sdajem.events';
			$data['parent_id'] = 1;
			$data['published'] = 1;

			$catModel = new CategoryModel();

			$return = $this->input->get('returnEdit', null, 'base64');
			if (empty($return) || !Uri::isInternal(base64_decode($return))) {
				$return = Uri::base();
			}

			$this->setRedirect(Route::_(base64_decode($return), false));

			return $catModel->save($data);
		}

		return true;
	}
}