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

/**
 * @package     Sda\Jem\Site\Controller
 *
 * @since       0.0.1
 */
class Event extends DataController
{
	/**
	 * Saves the comment for the Event
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
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function registerAttendee()
	{
		$referer = $this->input->server->getString('HTTP_REFERER');

		$input = $this->input->request->post->getArray();

		if ($input['eventId'] && $input['action'])
		{
			/** @var \Sda\Jem\Site\Model\Event $event */
			$event = $this->getModel('Event');
			$event->load((int) $input['eventId']);

			/** @var Attendee $attendee */
			$attendee = $event->getNew('attendees');

			if ($input['attendeeId'])
			{
				$attendee->sdajem_attendee_id = $input['attendeeId'];
			}

			$attendee->users_user_id = Factory::getUser()->id;
			$attendee->event = (int) $input['eventId'];
			$attendee->status = (int) $input['action'];

			$attendee->save();
		}

		$this->setRedirect($referer);
	}

	/**
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
	 * Changes the event Status
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
		RefererHelper::setReferer($this->input->server->getString('HTTP_REFERER'));
		$this->setRedirect('index.php?option=com_sdajem&view=Location&task=add');
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
		RefererHelper::setReferer($referer);
		$this->setRedirect('index.php?option=com_sdajem&view=Categories&task=add&type=1');
		$this->redirect();
	}
	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeRead()
	{
		RefererHelper::setReferer($this->input->server->getString('HTTP_REFERER'));
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeEdit()
	{
		RefererHelper::setReferer($this->input->server->getString('HTTP_REFERER'));
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeAdd()
	{
		RefererHelper::setReferer($this->input->server->getString('HTTP_REFERER'));
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onAfterSave()
	{
		$this->setRedirect(RefererHelper::getReferer());
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onAfterCancel()
	{
		$this->setRedirect(RefererHelper::getReferer());
	}
}
