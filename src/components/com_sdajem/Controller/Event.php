<?php
/**
 * @package     Sda\Jem\Site\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Site\Controller;

use FOF30\Controller\DataController;
use Joomla\CMS\Factory;
use Sda\Jem\Site\Model\Attendee;
use Sda\Jem\Site\Model\Comment;
use FOF30\Date\Date;
use Joomla\CMS\Language\Text;
use Sda\Jem\Admin\Helper\RefererHelper;
use Sda\Jem\Admin\Model\Mailing;
use FOF30\Container\Container;
use Sda\Profiles\Admin\Model\Fitting;

/**
 * @package     Sda\Jem\Site\Controller
 *
 * @since       0.0.1
 */
class Event extends DataController
{
	/**
	 * Saves a comment for the Event
	 *
	 * @return void
	 * @since 0.0.1
	 * @throws \Exception
	 */
	public function comment()
	{
		// After saving we want to return to where we came from
		$referer = $this->input->server->getString('HTTP_REFERER');

		$input = $this->input->request->post->getArray();

		if ($input['eventId'] && $input['comment'])
		{
			/** @var \Sda\Jem\Site\Model\Event $event */
			$event = $this->getModel('Event');
			$event->load((int) $input['eventId']);

			/** @var Comment $comment */
			$comment                = $event->getNew('comments');
			$comment->users_user_id = Factory::getUser()->id;
			$comment->comment       = $input['comment'];
			$comment->timestamp     = new Date($this->input->server->getString('REQUEST_TIME'));
			$comment->save();
		}
		else
		{
			Factory::getApplication()->enqueueMessage(Text::_('SOME_ERROR_OCCURRED'), 'warning');
		}

		$this->setRedirect($referer);
	}

	/**
	 * Deletes a comment for an event
	 *
	 * @return void
	 * @since 0.0.1
	 * @throws \Exception
	 */
	public function deleteComment()
	{
		$referer = $this->input->server->getString('HTTP_REFERER');

		if ($id = $this->input->get('id'))
		{
			$this->getModel('Comment')->delete($id);
		}
		else
		{
			Factory::getApplication()->enqueueMessage(Text::_('SOME_ERROR_OCCURRED'), 'error');
		}

		$this->setRedirect($referer);
	}

	/**
	 * Method to get the Html for the Register Buttons via Ajax Call
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function getRegisterHtmlAjax()
	{
		$input = $this->input->request->post->getArray();

		if ($input['eventId'])
		{
			$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Events&task=getRegisterHtml&id=' . $input['eventId']);
		}
		else
		{
			$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Event&task=error');
		}
		$this->redirect();
	}

	/**
	 * Changes the event Status and calls the raw View to render the new State and possibilitys
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function changeEventStatusAjax()
	{
		$input = $this->input->request->post->getArray();

		if ($input['id'] && $input['status'])
		{
			/** @var \Sda\Jem\Site\Model\Event $event */
			$event = $this->getModel();
			$event->load($input['id']);
			$event->eventStatus = $input['status'];
			$event->save();

			$eventName = 'onAfterStatusChanged';
			$result = $this->triggerEvent($eventName, array($event));

			$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Events&task=changeEventStatus&id=' . $input['id']);
		}
		else
		{
			$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Event&task=error');
		}

		$this->redirect();
	}

	/**
	 * Redirect to add a location
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function addNewLocation()
	{
		$input = $this->input->getArray();

		if ($input['sdajem_event_id'] == '')
		{
			$referer = 'index.php?option=com_sdajem&view=Events&task=add';
		}
		else
		{
			$referer = 'index.php?option=com_sdajem&view=Events&task=edit&id='.$input['sdajem_event_id'];
		}

		try
		{
			Factory::getApplication()->setUserState('eventState', $input);
		}
		catch (\Exception $e)
		{
		}

		RefererHelper::setReferer($referer);

		$this->setRedirect('index.php?option=com_sdajem&view=Location&task=add');
		$this->redirect();
	}

	/**
	 * Redirect to add a Contact
	 *
	 * @return void
	 *
	 * @since 0.7.0
	 */
	public function addNewContact()
	{
		$input = $this->input->getArray();

		if ($input['sdajem_event_id'] == '')
		{
			$referer = 'index.php?option=com_sdajem&view=Events&task=add';
		}
		else
		{
			$referer = 'index.php?option=com_sdajem&view=Events&task=edit&id='.$input['sdajem_event_id'];
		}

		try
		{
			Factory::getApplication()->setUserState('eventState', $input);
		}
		catch (\Exception $e)
		{
		}

		RefererHelper::setReferer($referer);

		$this->setRedirect('index.php?option=com_sdacontacts&view=Contact&task=add');
		$this->redirect();
	}

	/**
	 * Redirect to add a category
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function addNewCategory()
	{
		$input = $this->input->getArray();

		if ($input['sdajem_event_id'] == '')
		{
			$referer = 'index.php?option=com_sdajem&view=Events&task=add';
		}
		else
		{
			$referer = 'index.php?option=com_sdajem&view=Events&task=edit&id='.$input['sdajem_event_id'];
		}

		try
		{
			Factory::getApplication()->setUserState('eventState', $input);
		}
		catch (\Exception $e)
		{
		}

		RefererHelper::setReferer($referer);
		$this->setRedirect('index.php?option=com_sdajem&view=Categories&task=add&type=1');
		$this->redirect();
	}

	/**
	 * Sets the referer
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeRead()
	{
		$referer = $this->input->server->getString('HTTP_REFERER');

		if ($referer != null)
		{
			RefererHelper::setReferer($referer);
		}
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeEdit()
	{
		$referer = $this->input->server->getString('HTTP_REFERER');

		if ($referer != null)
		{
			RefererHelper::setReferer($referer);
		}
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeAdd()
	{
		$referer = $this->input->server->getString('HTTP_REFERER');

		if ($referer != null)
		{
			RefererHelper::setReferer($referer);
		}

		try
		{
			$eventState = Factory::getApplication()->getUserState('eventState');

			if ($eventState)
			{
				$this->defaultsForAdd = $eventState;
				Factory::getApplication()->setUserState('eventState', null);
			}
		}
		catch (\Exception $e)
		{
		}
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onAfterSave()
	{
		/** @var \Sda\Jem\Admin\Model\Event $event */
		$event = $this->getModel();

		// Soll Ausrüstung bei Anmeldung berücksichtigt werden
		if ((bool) $event->useFittings)
		{
			$inArray = false;
			$groupsInArray = array();

			// Haben wir bereits das StandardProfil als Teilnehmer
			if ($event->attendees)
			{
				/** @var Attendee $attendee */
				foreach ($event->attendees as $attendee)
				{
					$inArray = ($attendee->sdaprofiles_profile_id == $event->fittingProfile);

					if ((bool) $attendee->profile->groupProfile)
					{
						array_push($groupsInArray, $attendee->sdajem_attendee_id);
					}
				}
			}

			if($inArray == false)
			{
				/** @var Attendee $attendee */
				$attendee = $this->container->factory->model('Attendee');

				// GroupProfile has changed
				if (count($groupsInArray) > 0)
				{
					foreach ($groupsInArray as $id)
					{
						$attendee->forceDelete($id);
					}
				}

				// Now we can save the new one
				$attendee->sdaprofiles_profile_id = $event->fittingProfile;
				$attendee->sdajem_event_id = $event->sdajem_event_id;
				$attendee->status = 1;

				$equipment = array();

				/** @var Fitting $fitting */
				foreach ($event->fProfile->fittings as $fitting)
				{
					array_push($equipment, $fitting->sdaprofiles_fitting_id);
				}

				$attendee->sdaprofilesFittingIds = $equipment;
				$attendee->save();
			}
		}

		$this->clearEventState();

		$this->setRedirect(RefererHelper::getReferer());

		// It is possible that we would be redirected to a Category add but we want to go to brose view after save
		$this->setRedirect('index.php?option=com_sdajem&view=Events&task=browse');
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeCancel()
	{
		$this->clearEventState();

		$this->setRedirect(RefererHelper::getReferer());
	}

	/**
	 * @return void
	 *
	 * @since 0.0.8
	 */
	public function archiveEvent()
	{
		$input = $this->input->getArray();
		$event = $this->getModel();
		$event->load($input['sdajem_event_id']);
		$event->archive();
		$this->setRedirect('index.php?option=com_sdajem&view=Events&task=browse');
	}

	/**
	 * Subscribe for eMail notification for an event
	 *
	 * @return void
	 *
	 * @since 0.1.1
	 */
	public function subscribeAjax()
	{
		$userId = Factory::getUser()->id;
		$eventId = $this->input->getInt('eventId');
		/** @var Mailing $mailingModel */
		$mailingModel = Container::getInstance('com_sdajem')->factory->model('Mailing');
		$mailingModel->getSubscriptionForUserAndEvent($eventId, $userId);

		if (!$mailingModel->sdajem_mailing_id)
		{
			$mailingModel->users_user_id = $userId;
			$mailingModel->sdajem_event_id = $eventId;
		}

		$mailingModel->subscribed = $this->input->get('subscribed');
		$mailingModel->save();
		$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Events&task=subscribe&id=' . $eventId);
	}

	/**
	 * Needed for Plugin triggering
	 *
	 * @return void
	 *
	 * @since 0.1.5
	 */
	public function apply()
	{
		parent::apply();
	}

	/**
	 * Needed for Plugin triggering
	 *
	 * @return boolean true on success
	 *
	 * @since 0.1.5
	 */
	protected function applySave()
	{
		return parent::applySave();
	}

	/**
	 * Needed for Plugin triggering
	 *
	 * @return void
	 *
	 * @since 0.1.5
	 */
	public function save()
	{
		parent::save();
	}

	/**
	 * Clear the saved event Data in the userState
	 *
	 * @return void
	 *
	 * @since 0.2.9
	 */
	private function clearEventState()
	{
		try
		{
			$eventState = Factory::getApplication()->getUserState('eventState');

			if ($eventState)
			{
				Factory::getApplication()->setUserState('eventState', null);
			}
		}
		catch (\Exception $e)
		{
		}
	}

	/**
	 * Dispatches to Raw view to reload the planingTool after registering a new attendee
	 *
	 * @return void
	 *
	 * @since 0.3.3
	 */
	public function reloadPlaningAjax()
	{
		$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Events&task=reloadPlaning&id=' . $this->input->get('eventId'));
	}

	/**
	 * Saves a working state of the planingTool
	 *
	 * @return void
	 *
	 * @since 0.3.5
	 */
	public function savePlan()
	{
		$svg = $_POST['svg'];
		/** @var \Sda\Jem\Admin\Model\Event $event */
		$event = $this->getModel('Event');
		$event->load($_POST['id']);
		$event->svg = $svg;
		$event->save();
	}

	/**
	 * @return void
	 *
	 * @since 0.4.1
	 */
	protected function onBeforeSave()
	{
		$this->checkFields();
	}

	/**
	 * @return void
	 *
	 * @since 0.4.1
	 */
	protected function onBeforeApply()
	{
		$this->checkFields();
	}

	/**
	 * @return void
	 *
	 * @since 0.4.1
	 */
	private function checkFields()
	{
		$input = $this->input->getArray();

		if ($input['sdajem_event_id'] == '')
		{
			$referer = 'index.php?option=com_sdajem&view=Events&task=add';
		}
		else
		{
			$referer = 'index.php?option=com_sdajem&view=Events&task=edit&id=' . $input['sdajem_event_id'];
		}

		try
		{
			Factory::getApplication()->setUserState('eventState', $input);
		}
		catch (\Exception $e)
		{
		}

		/** @var \Sda\Jem\Admin\Model\Event $model */
		$model = $this->getModel();

		if (!$model->checkFields())
		{
			$this->setRedirect($referer);
			$this->redirect();
		}
	}

	/**
	 * @since 0.6.0
	 */
	public function eventsFlexView()
	{
		$this->setDefaultView(0);
	}

	/**
	 * @since 0.6.0
	 */
	public function eventsBoxedView()
	{
		$this->setDefaultView(1);
	}

	/**
	 * @param int $view
	 *
	 * @since 0.6.0
	 */
	private function setDefaultView(int $view)
	{
		try {
			$app = Factory::getApplication();
			$app->setUserState("com_sdajem.eventsView", $view);
		} catch (\Exception $e) {
		}

		$this->setRedirect('index.php?option=com_sdajem&view=Events&task=browse');
		$this->redirect();
	}

}
