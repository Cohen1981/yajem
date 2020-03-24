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
use Sda\Jem\Admin\Helper\EventStatusHelper;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */
$event = $this->getModel('Event');
$this->addCssFile('media://com_sdajem/css/sdajem_style.css');
$this->addJavascriptFile('media://com_sdajem/js/status.js');

switch ($event->eventStatus)
{
	case 0:
		echo "<h3>" . EventStatusHelper::getOpenSymbol() .
			Text::_('SDAJEM_EVENT_STATUS_OPEN') . "</h3>";
		break;
	case 1:
		echo "<h3>" . EventStatusHelper::getConfirmedSymbol() .
			Text::_('SDAJEM_EVENT_STATUS_CONFIRMED') . "</h3>";
		break;
	case 2:
		echo "<h3>" . EventStatusHelper::getCanceledSymbol() .
			Text::_('SDAJEM_EVENT_STATUS_CANCELED') . "</h3>";
		break;
	case 3:
		echo "<h3>" . EventStatusHelper::getCheckedSymbol() .
			Text::_('COM_SDAJEM_EVENT_STATUS_CHECKED') . "</h3>";
}

if ($event->organizerId == Factory::getUser()->id)
{
	switch ($event->eventStatus)
	{
		case 0:
			echo "<button id=\"checkedButton\" type=\"button\" onclick=\"changeEventStatus(3)\" name=\"checkedButton\">";
			echo EventStatusHelper::getCheckedSymbol();
			echo "</button>";
			echo "<button id=\"confirmButton\" type=\"button\" onclick=\"changeEventStatus(1)\" name=\"confirmButton\">";
			echo EventStatusHelper::getConfirmedSymbol();
			echo "</button>";
			echo "<button id=\"cancelButton\" type=\"button\" onclick=\"changeEventStatus(2)\" name=\"cancelButton\">";
			echo EventStatusHelper::getCanceledSymbol();
			echo "</button>";
			break;
		case 1:
			echo "<button id=\"cancelButton\" type=\"button\" onclick=\"changeEventStatus(2)\" name=\"cancelButton\">";
			echo EventStatusHelper::getCanceledSymbol();
			echo "</button>";
			break;
		case 2:
			echo "<button id=\"confirmButton\" type=\"button\" onclick=\"changeEventStatus(1)\" name=\"confirmButton\">";
			echo EventStatusHelper::getConfirmedSymbol();
			echo "</button>";
			break;
		case 3:
			echo "<button id=\"confirmButton\" type=\"button\" onclick=\"changeEventStatus(1)\" name=\"confirmButton\">";
			echo EventStatusHelper::getConfirmedSymbol();
			echo "</button>";
			echo "<button id=\"cancelButton\" type=\"button\" onclick=\"changeEventStatus(2)\" name=\"cancelButton\">";
			echo EventStatusHelper::getCanceledSymbol();
			echo "</button>";
			break;
	}
}
?>
<input type="hidden" id="eventId" name="id" value="<?php echo $event->sdajem_event_id; ?>">
