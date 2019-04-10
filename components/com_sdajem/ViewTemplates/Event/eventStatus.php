<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */
$event = $this->getModel('Event');
$this->addCssFile('media://com_sdajem/css/style.css');
$this->addJavascriptFile('media://com_sdajem/js/status.js');

switch ($event->eventStatus)
{
	case 0:
		echo "<h3><i class=\"fas fa-question-circle\" aria-hidden='true' title='" . Text::_('COM_SDAJEM_ICON_STATUS_OPEN') . "'></i> " .
			Text::_('SDAJEM_EVENT_STATUS_OPEN') . "</h3>";
		break;
	case 1:
		echo "<h3><i class=\"fas fa-thumbs-up\" style=\"color: #51a351;\" aria-hidden='true' title='" .
			Text::_('COM_SDAJEM_ICON_STATUS_CONFIRMED') . "'></i> " .
			Text::_('SDAJEM_EVENT_STATUS_CONFIRMED') . "</h3>";
		break;
	case 2:
		echo "<h3><i class=\"fas fa-thumbs-down\" style=\"color: #ff6b6b;\" aria-hidden='true' title='" .
			Text::_('COM_SDAJEM_ICON_STATUS_CANCELED') . "'></i> " .
			Text::_('SDAJEM_EVENT_STATUS_CANCELED') . "</h3>";
		break;
}

if ($event->organizerId == Factory::getUser()->id)
{
	switch ($event->eventStatus)
	{
		case 0:
			echo "<button id=\"confirmButton\" type=\"button\" onclick=\"changeEventStatus(1)\" name=\"confirmButton\">";
			echo "<i class=\"fas fa-thumbs-up\" style=\"color: #51a351;\" aria-hidden='true' title='" .
				Text::_('COM_SDAJEM_ICON_STATUS_CONFIRMED') . "'></i>";
			echo "</button>";
			echo "<button id=\"cancelButton\" type=\"button\" onclick=\"changeEventStatus(2)\" name=\"cancelButton\">";
			echo "<i class=\"fas fa-thumbs-down\" style=\"color: #ff6b6b;\" aria-hidden='true' title='" .
				Text::_('COM_SDAJEM_ICON_STATUS_CANCELED') . "'></i>";
			echo "</button>";
			break;
		case 1:
			echo "<button id=\"cancelButton\" type=\"button\" onclick=\"changeEventStatus(2)\" name=\"cancelButton\">";
			echo "<i class=\"fas fa-thumbs-down\" style=\"color: #ff6b6b;\" aria-hidden='true' title='" .
				Text::_('COM_SDAJEM_ICON_STATUS_CANCELED') . "'></i>";
			echo "</button>";
			break;
		case 2:
			echo "<button id=\"confirmButton\" type=\"button\" onclick=\"changeEventStatus(1)\" name=\"confirmButton\">";
			echo "<i class=\"fas fa-thumbs-up\" style=\"color: #51a351;\" aria-hidden='true' title='" .
				Text::_('COM_SDAJEM_ICON_STATUS_CONFIRMED') . "'></i>";
			echo "</button>";
			break;
	}
}
?>
<input type="hidden" id="eventId" name="id" value="<?php echo $event->sdajem_event_id; ?>">
