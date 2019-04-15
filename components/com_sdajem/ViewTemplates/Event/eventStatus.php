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
use Sda\Jem\Admin\Helper\HtmlHelper;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */
$event = $this->getModel('Event');
$this->addCssFile('media://com_sdajem/css/style.css');
$this->addJavascriptFile('media://com_sdajem/js/status.js');

switch ($event->eventStatus)
{
	case 0:
		echo "<h3>" . HtmlHelper::getOpenSymbol() .
			Text::_('SDAJEM_EVENT_STATUS_OPEN') . "</h3>";
		break;
	case 1:
		echo "<h3>" . HtmlHelper::getConfirmedSymbol() .
			Text::_('SDAJEM_EVENT_STATUS_CONFIRMED') . "</h3>";
		break;
	case 2:
		echo "<h3>" . HtmlHelper::getCanceledSymbol() .
			Text::_('SDAJEM_EVENT_STATUS_CANCELED') . "</h3>";
		break;
	case 3:
		echo "<h3>" . HtmlHelper::getCheckedSymbol() .
			Text::_('SDAJEM_EVENT_STATUS_CHECKED') . "</h3>";
}

if ($event->organizerId == Factory::getUser()->id)
{
	switch ($event->eventStatus)
	{
		case 0:
			echo "<button id=\"checkedButton\" type=\"button\" onclick=\"changeEventStatus(3)\" name=\"checkedButton\">";
			echo HtmlHelper::getCheckedSymbol();
			echo "</button>";
			echo "<button id=\"confirmButton\" type=\"button\" onclick=\"changeEventStatus(1)\" name=\"confirmButton\">";
			echo HtmlHelper::getConfirmedSymbol();
			echo "</button>";
			echo "<button id=\"cancelButton\" type=\"button\" onclick=\"changeEventStatus(2)\" name=\"cancelButton\">";
			echo HtmlHelper::getCanceledSymbol();
			echo "</button>";
			break;
		case 1:
			echo "<button id=\"cancelButton\" type=\"button\" onclick=\"changeEventStatus(2)\" name=\"cancelButton\">";
			echo HtmlHelper::getCanceledSymbol();
			echo "</button>";
			break;
		case 2:
			echo "<button id=\"confirmButton\" type=\"button\" onclick=\"changeEventStatus(1)\" name=\"confirmButton\">";
			echo HtmlHelper::getConfirmedSymbol();
			echo "</button>";
			break;
		case 3:
			echo "<button id=\"confirmButton\" type=\"button\" onclick=\"changeEventStatus(1)\" name=\"confirmButton\">";
			echo HtmlHelper::getConfirmedSymbol();
			echo "</button>";
			echo "<button id=\"cancelButton\" type=\"button\" onclick=\"changeEventStatus(2)\" name=\"cancelButton\">";
			echo HtmlHelper::getCanceledSymbol();
			echo "</button>";
			break;
	}
}
?>
<input type="hidden" id="eventId" name="id" value="<?php echo $event->sdajem_event_id; ?>">
