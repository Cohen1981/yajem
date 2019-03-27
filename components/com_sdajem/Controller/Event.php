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
}