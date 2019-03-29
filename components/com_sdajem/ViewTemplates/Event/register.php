<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use FOF30\Container\Container;

$currentUser = Factory::getUser()->id;
$status = 0;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */
/** @var \Sda\Jem\Site\Model\Attendee $attendee */
$event = $this->getModel('Event');
$attendee = Container::getInstance('com_sdajem')->factory->model('attendee');
$attendee->getAttendeeForEventAndUser($currentUser, $event->sdajem_event_id);

if ($attendee->sdajem_attendee_id)
{
	$status = $attendee->status;
	$html = $html . "<input id=\"attendeeId\" type=\"hidden\" name=\"attendeeId\" value=\"" . $attendee->sdajem_attendee_id . "\"/>";
}

switch ($status)
{
	case 0:
		$buttons = "<button id=\"register\" type=\"button\" form=\"attendeeForm\" name=\"action\" value=\"1\" onclick=\"registerAjax(1)\">" .
			Text::_('SDAJEM_REGISTER') . "</button>";
		$buttons = $buttons .
			"<button id=\"unregister\" type=\"button\" form=\"attendeeForm\" name=\"action\" value=\"2\" onclick=\"registerAjax(2)\">" .
			Text::_('SDAJEM_UNREGISTER') . "</button>";
		break;
	case 1:
		$buttons = "<button id=\"unregister\" type=\"button\" form=\"attendeeForm\" name=\"action\" value=\"2\" onclick=\"registerAjax(2)\">" .
			Text::_('SDAJEM_UNREGISTER') . "</button>";
		break;
	case 2:
		$buttons = "<button id=\"register\" type=\"button\" form=\"attendeeForm\" name=\"action\" value=\"1\" onclick=\"registerAjax(1)\">" .
			Text::_('SDAJEM_REGISTER') . "</button>";
		break;
}

$html = $html . $buttons;

echo $html;