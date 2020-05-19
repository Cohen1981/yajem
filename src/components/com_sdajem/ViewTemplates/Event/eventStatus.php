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
use Sda\Html\Helper;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */
$event = $this->getModel('Event');
$this->addCssFile('media://com_sdajem/css/sdajem_style.css');
$this->addJavascriptFile('media://com_sdajem/js/status.js');

switch ($event->eventStatus)
{
	case 0:
		echo "<h3>" . Helper::getOpenSymbol() . "</h3>";
		break;
	case 1:
		echo "<h3>" . Helper::getConfirmedSymbol() . "</h3>";
		break;
	case 2:
		echo "<h3>" . Helper::getCanceledSymbol() . "</h3>";
		break;
	case 3:
		echo "<h3>" . Helper::getCheckedSymbol() . "</h3>";
}

if ($event->organizerId == Factory::getUser()->id && !(bool)$event->eventCancelled)
{
	echo Helper::getEventStatusChanger($event);
}
?>
<input type="hidden" id="eventId" name="id" value="<?php echo $event->sdajem_event_id; ?>">
