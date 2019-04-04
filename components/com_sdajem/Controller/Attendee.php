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

class Attendee extends DataController
{
	public function registerAttendeeAjax()
	{
		$input = $this->input->request->post->getArray();

		if ($input['eventId'] && $input['action'])
		{
			/** @var \Sda\Jem\Site\Model\Attendee $attendee */
			$attendee = $this->getModel();

			if ($input['attendeeId'])
			{
				$attendee->sdajem_attendee_id = $input['attendeeId'];
			}

			$attendee->users_user_id = Factory::getUser()->id;
			$attendee->sdajem_event_id = (int) $input['eventId'];
			$attendee->status = (int) $input['action'];
			$attendee->sdaprofilesFittingIds = $input['fittings'];

			$attendee->save();

			$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Attendee&task=registerAjax&id=' . $attendee->sdajem_attendee_id);
		}
		else
		{
			$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Attendee&task=error');
		}
	}
}