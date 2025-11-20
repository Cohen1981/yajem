<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

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
use Joomla\CMS\Access\Access;
use Joomla\CMS\Application\CMSWebApplicationInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormFactoryInterface;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Mail\MailerFactoryInterface;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\View\ViewInterface;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Router\Router;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\UserFactoryInterface;
use Joomla\Component\Categories\Administrator\Model\CategoryModel;
use Joomla\CMS\Language\Text;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Sda\Component\Sdajem\Administrator\Helper\AttendingHelper;
use Sda\Component\Sdajem\Administrator\Helper\InterestHelper;
use Sda\Component\Sdajem\Administrator\Model\AttendingModel;
use Sda\Component\Sdajem\Administrator\Model\InterestModel;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Enums\IntAttStatusEnum;
use Sda\Component\Sdajem\Site\Model\AttendingformModel;
use Sda\Component\Sdajem\Site\Model\AttendingsModel;
use Sda\Component\Sdajem\Site\Model\CommentformModel;
use Sda\Component\Sdajem\Site\Model\CommentsModel;
use Sda\Component\Sdajem\Site\Model\EventformModel;
use Sda\Component\Sdajem\Site\Model\EventModel;
use Sda\Component\Sdajem\Site\Model\InterestformModel;
use Sda\Component\Sdajem\Site\Model\InterestsModel;
use Sda\Component\Sdajem\Site\Model\Item\Comment;

class EventController extends FormController
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $view_item = 'eventform';

	protected $view_list = 'events';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  BaseDatabaseModel  The model.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getModel($name = 'eventform', $prefix = '', $config = ['ignore_request' => true]):BaseDatabaseModel
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
	 * @throws Exception
	 * @since   __DEPLOY_VERSION__
	 */
	protected function allowAdd($data = []):bool
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
	 * @throws Exception
	 * @since   __DEPLOY_VERSION__
	 */
	protected function allowEdit($data = [], $key = 'id'):bool
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
	public function save($key = null, $urlVar = null):bool
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
	public function cancel($key = null):bool
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
	public function delete(): bool
	{
		$pks = $this->input->get('cid') ?? $this->input->get('id');

		if (!is_array($pks)) {
			$pks = [$pks];
		}

		$attendingFormModel = new AttendingformModel();
		$attendingsModel = new AttendingsModel();

		$interestFormModel = new InterestformModel();
		$interestsModel = new InterestsModel();

		$commentsModel = new CommentsModel();
		$commentFormModel = new CommentformModel();

		foreach ($pks as &$pk) {
			$attendings = $attendingsModel->getAttendingsIdToEvent($pk);
			$attResult = $attendingFormModel->delete($attendings);

			$interests = $interestsModel->getInterestsIdToEvent($pk);
			$intResult = $interestFormModel->delete($interests);

			$comments = $commentsModel->getCommentsIdsToEvent($pk);
			$commentResult = $commentFormModel->delete($comments);
		}

		$eventFormModel = new EventformModel();

		if ($attResult && $intResult && $commentResult)
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
	protected function getRedirectToItemAppend($recordId = 0, $urlVar = 'id'):string
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
	protected function getReturnPage():string
	{
		$return = $this->input->get('return', null, 'base64');
		if (empty($return) || !Uri::isInternal(base64_decode($return))) {
			return Uri::base();
		}
		return base64_decode($return);
	}

	/**
	 * @since 1.2.4
	 */
	private function setViewLevel():void
	{
		$params = ComponentHelper::getParams('com_sdajem');
		$this->setAccessLevel($params->get('sda_public_planing'));
	}

	/**
	 *
	 *
	 * @since 1.0.9
	 */
	public function open():void
	{
		$this->setEventStatus(EventStatusEnum::OPEN);
		$this->setViewLevel();
		$this->setRedirect(Route::_($this->getReturnPage(), false));
	}

	public function applied():void
	{
		$this->setEventStatus(EventStatusEnum::APPLIED);
		$this->setViewLevel();
		$this->setRedirect(Route::_($this->getReturnPage(), false));
	}

	public function canceled():void
	{
		$this->setEventStatus(EventStatusEnum::CANCELED);
		$this->setViewLevel();
		$this->setRedirect(Route::_($this->getReturnPage(), false));
	}

	public function confirmed():void
	{
		$this->setEventStatus(EventStatusEnum::CONFIRMED);
		$this->setAccessLevel(1);
		$this->setRedirect(Route::_($this->getReturnPage(), false));
	}

	/**
	 *
	 *
	 * @since 1.0.9
	 */
	public function planing():void
	{
		$this->setEventStatus(EventStatusEnum::PLANING);
		$this->setViewLevel();
		$this->setRedirect(Route::_($this->getReturnPage(), false));
	}

	protected function setEventStatus(EventStatusEnum $enum):void
	{
		$eventId = $this->input->get('eventId');

		if ($eventId != null) {
			/* @var EventformModel $event */
			$event = $this->getModel();
			$event->updateEventStatus($eventId, $enum);
		}
	}

	protected function setAccessLevel(int $access):void
	{
		$eventId = $this->input->get('eventId');

		if ($eventId != null) {
			/* @var EventformModel $event */
			$event = $this->getModel();
			$event->updateEventAccess($eventId, $access);
		}
	}

	public function addCategory():bool
	{
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

	/**
	 * @param   null  $eventId
	 * @param   null  $userId
	 *
	 * @throws Exception
	 * @since 1.1.3
	 */
	public function positive($eventId = null, $userId = null):void
	{
		//$this->option = 'core.manage.attending';
		$pks = [];

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
				$eventModel = new EventModel();
				/* @var EventModel $event */
				$event = $eventModel->getItem($id);

				if ($event->eventStatus == EventStatusEnum::PLANING->value)
				{
					$interest = InterestHelper::getInterestStatusToEvent($currUser->id, $id);
					$model = new InterestModel();
				} else
				{
					$interest = AttendingHelper::getAttendingStatusToEvent($currUser->id, $id);
					$model = new AttendingModel();
				}

				$data = array(
					'id'            => $interest->id,
					'event_id'      => $id,
					'users_user_id' => $currUser->id,
					'status'        => IntAttStatusEnum::POSITIVE->value);
					//'comment'       => $this->input->getRaw('comment')
//				);
				if ($event->eventStatus == EventStatusEnum::PLANING->value)
				{
					$data['comment'] = $this->input->getRaw('comment');
				} else {
					$data['fittings'] = $this->input->get('fittings');
				}

				$this->setRedirect(Route::_($this->getReturnPage(), false));
				$model->save($data);
			}
		}
	}

	/**
	 * @param   null  $eventId
	 * @param   null  $userId
	 *
	 * @throws Exception
	 * @since 1.1.3
	 */
	public function negative($eventId = null, $userId = null):void
	{
		//$this->option = 'core.manage.attending';
		$pks = [];

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
				$eventModel = new EventModel();
				/* @var EventModel $event */
				$event = $eventModel->getItem($id);

				if ($event->eventStatus == EventStatusEnum::PLANING->value)
				{
					$interest = InterestHelper::getInterestStatusToEvent($currUser->id, $id);
					$model = new InterestModel();
				} else
				{
					$interest = AttendingHelper::getAttendingStatusToEvent($currUser->id, $id);
					$model = new AttendingModel();
				}

				$data = array(
					'id'            => $interest->id,
					'event_id'      => $id,
					'users_user_id' => $currUser->id,
					'status'        => IntAttStatusEnum::NEGATIVE->value);
				//'comment'       => $this->input->getRaw('comment')
//				);
				if ($event->eventStatus == EventStatusEnum::PLANING->value)
				{
					$data['comment'] = $this->input->getRaw('comment');
				} else {
					$data['fittings'] = '';
				}

				$this->setRedirect(Route::_($this->getReturnPage(), false));
				$model->save($data);
			}
		}
	}

	/**
	 * @param   null  $eventId
	 * @param   null  $userId
	 *
	 * @throws Exception
	 * @since 1.1.3
	 */
	public function guest($eventId = null, $userId = null):void
	{
		//$this->option = 'core.manage.attending';
		$pks = [];

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
				$eventModel = new EventModel();
				/* @var EventModel $event */
				$event = $eventModel->getItem($id);

				if ($event->eventStatus == EventStatusEnum::PLANING->value)
				{
					$interest = InterestHelper::getInterestStatusToEvent($currUser->id, $id);
					$model = new InterestModel();
				} else
				{
					$interest = AttendingHelper::getAttendingStatusToEvent($currUser->id, $id);
					$model = new AttendingModel();
				}

				$data = array(
					'id'            => $interest->id,
					'event_id'      => $id,
					'users_user_id' => $currUser->id,
					'status'        => IntAttStatusEnum::GUEST->value);
				if ($event->eventStatus == EventStatusEnum::PLANING->value)
				{
					$data['comment'] = $this->input->getRaw('comment');
				} else {
					$data['fittings'] = '';
				}

				$this->setRedirect(Route::_($this->getReturnPage(), false));
				$model->save($data);
			}
		}
	}

	/**
	 * Saves a working state of the planingTool
	 *
	 * @return void
	 *
	 * @since 1.2.0
	 */
	public function savePlan():void
	{
		$svg = $_POST['svg'];
		if (empty($svg)) {
			return;
		}
		$this->app->setUserState('com_sdajem.event.callContext', $this->input->get('callContext', ''));

		/** @var EventModel $event */
		$event = $this->getModel('Event');
		$data = $event->getItem($_POST['id']);

		$reg = new Registry($_POST['svg']);

		$data->svg = $reg->toArray();

		$eventForm = new EventformModel();
		try
		{
			$eventForm->save($data->toArray());
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}
	}

	public function changeTpl():void
	{
		$user = Factory::getApplication()->getIdentity();
		$template = ($user->getParam('events_tpl', 'default') === 'default') ? 'cards' : 'default';
		$user->setParam('events_tpl', $template);
		$user->save();
		$this->setRedirect(Route::_($this->getReturnPage(), false));
	}

	/**
	 * @param   BaseDatabaseModel  $model
	 * @param                      $validData
	 *
	 * @return void
	 * @throws Exception
	 *
	 * @since 1.5.0
	 */
	protected function postSaveHook(BaseDatabaseModel $model, $validData = []):void
	{
		$componentParams = ComponentHelper::getParams('com_sdajem');

		if ($model->state->get('eventform.new') && $componentParams->get('sda_mail_on_new_event'))
		{
			$mailer = Factory::getContainer()->get(MailerFactoryInterface::class)->createMailer();

			// Define necessary variables
			$subject = Text::_('NEW_EVENT_SAVED') . ': '
				. $validData['title'] . ' '
				. HTMLHelper::date($validData['startDateTime'],'d.m.Y')
				. ' - ' . HTMLHelper::date($validData['endDateTime'], 'd.m.Y');
			$body = Text::_('COM_SDAJEM_FIELD_REGISTERUNTIL_LABEL') . ': ' . HTMLHelper::date($validData['registerUntil'],'d.m.Y');

			$recipientsUsers = Access::getUsersByGroup($componentParams->get('sda_usergroup_mail'));
			$userFactory = Factory::getContainer()->get(UserFactoryInterface::class);

			foreach ($recipientsUsers as $recipientUser)
			{
				$mailer->addRecipient($userFactory->loadUserById($recipientUser)->email);
			}

			// Set subject, and body of the email
			$mailer
				->isHTML(true)
				->setSubject($subject)
				->setBody($body);

			// Set plain text alternative body (for email clients that don't support HTML)
			$mailer->AltBody = strip_tags($body);

			// Send the email and check for success or failure
			try {
				$send = $mailer->Send(); // Attempt to send the email

				if ($send !== true) {
					echo 'Error: ' . $send->__toString(); // Display error message if sending fails
				} else {
					Factory::getApplication()->enqueueMessage(Text::_('SDA_EMAIL_EVENT_SUCCESS'), 'info');
				}
			} catch (Exception $e) {
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			}
		}
	}
}