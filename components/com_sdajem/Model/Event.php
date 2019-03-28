<?php
/**
 * @package     Sda\Jem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Site\Model;

use Sda\Jem\Admin\Model\Event as AdminEvent;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

/**
 * @package     Sda\Jem\Site\Model
 *
 * @since       0.0.1
 */
class Event extends AdminEvent
{
	public function getRegisterHtml() : string
	{
		$currentUser = Factory::getUser()->id;
		$status = 0;
		$html = "";

		/** @var Attendee $attendee */
		foreach ($this->attendees as $attendee)
		{
			if ($attendee->users_user_id == $currentUser)
			{
				$status = $attendee->status;
				$html = $html . "<input type=\"hidden\" name=\"attendeeId\" value=\"" . $attendee->sdajem_attendee_id . "\"/>";
			}
		}

		switch ($status)
		{
			case 0:
				$buttons = "<button type=\"submit\" form=\"attendeeForm\" name=\"action\" value=\"1\">" .
					Text::_('SDAJEM_REGISTER') . "</button>";
				$buttons = $buttons .
					"<button type=\"submit\" form=\"attendeeForm\" name=\"action\" value=\"2\">" .
					Text::_('SDAJEM_UNREGISTER') . "</button>";
				break;
			case 1:
				$buttons = "<button type=\"submit\" form=\"attendeeForm\" name=\"action\" value=\"2\">" .
					Text::_('SDAJEM_UNREGISTER') . "</button>";
				break;
			case 2:
				$buttons = "<button type=\"submit\" form=\"attendeeForm\" name=\"action\" value=\"1\">" .
					Text::_('SDAJEM_REGISTER') . "</button>";
				break;
		}

		$html = $html . $buttons;

		return $html;
	}
}
