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

/**
 * @package     Sda\Jem\Site\Controller
 *
 * @since       0.0.5
 */
class Attendee extends DataController
{
	/**
	 *
	 * @return void
	 * @since 0.0.5
	 */
	public function registerAttendeeAjax()
	{
		$input = $this->input->request->post->getArray();

		if ($input['eventId'] && $input['action'])
		{
			/** @var \Sda\Jem\Site\Model\Attendee $attendee */
			$attendee = $this->getModel();

			if ($input['attendeeId'])
			{
				$attendee->load($input['attendeeId']);
			}

			/** @var \Sda\Jem\Admin\Model\Event $event */
			$event = $this->container->factory->model('Event');

			$event->load($input['eventId']);

			if (count($event->svg) > 0 && (int) $input['action'] == 2 && $attendee->sdajem_attendee_id)
			{
				$fittings = $attendee->sdaprofilesFittingIds;
				$svgElements = $event->svg;

				foreach ($fittings as $fid)
				{
					array_forget($svgElements, (string) 'index_' . $fid);
				}

				$event->svg = $svgElements;
				$event->store();
			}

			$attendee->users_user_id = Factory::getUser()->id;
			$attendee->sdajem_event_id = (int) $input['eventId'];
			$attendee->status = (int) $input['action'];
			$attendee->sdaprofilesFittingIds = $input['fittings'];

			$attendee->save();

			$eventName = 'onAfterRegisterAttendee';
			$this->triggerEvent($eventName, array($attendee));

			$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Attendee&task=registerAjax&id=' . $attendee->sdajem_attendee_id);
		}
		else
		{
			$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Attendee&task=error');
		}
	}
}