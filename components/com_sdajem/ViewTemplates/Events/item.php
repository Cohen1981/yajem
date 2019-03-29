<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Factory;
use FOF30\Date\Date;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

/** @var \Sda\Jem\Site\View\Event\Html  $this       */
/** @var \Sda\Jem\Site\Model\Event      $event      */
/** @var \Sda\Jem\Site\Model\Attendee   $attendee   */
/** @var \Sda\Jem\Site\Model\Comment    $comment    */

$this->addCssFile('media://com_sdajem/css/style.css');
$event = $this->getItem();

$startDate = new Date($event->startDateTime);
$endDate = new Date($event->endDateTime);

$guest = Factory::getUser()->guest;
?>
<form action="<?php echo JRoute::_('index.php?option=com_sdajem&view=Events'); ?>" method="post"
      name="adminForm" id="adminForm">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="id" value="<?php echo $event->sdajem_event_id; ?>">
	<?php echo JHtml::_('form.token'); ?>
</form>

<div class="sdajem_content_container">

<div class="sdajem_event_grid">

	<div class="sdajem_label">
		<h3>
			<?php echo Text::_('COM_SDAJEM_EVENT_TITLE_LABEL'); ?>
		</h3>
	</div>
	<div class="sdajem_value">
		<h3>
			<?php echo $event->title; ?>
		</h3>
	</div>

	<div class="sdajem_label">
		<h3>
			<?php echo Text::_('COM_SDAJEM_EVENT_EVENTSTATUS_LABEL'); ?>
		</h3>
	</div>
	<div id="eventStatus" class="sdajem_value">
		<?php echo $this->loadAnyTemplate('site:com_sdajem/Event/eventStatus'); ?>
	</div>

	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_EVENT_STARTDATETIME_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php
		if ((bool) $event->allDayEvent)
		{
			echo $startDate->format('d.m.Y');
		}
		else
		{
			echo $startDate->format('d.m.Y H:i');
		}
		?>
	</div>
	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_EVENT_ENDDATETIME_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php
		if ((bool) $event->allDayEvent)
		{
			echo $endDate->format('d.m.Y');
		}
		else
		{
			echo $endDate->format('d.m.Y H:i');
		}
		?>
	</div>
	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_EVENT_URL_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php echo $event->url; ?>
	</div>
	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_EVENT_DESCRIPTION_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php echo $event->description; ?>
	</div>

</div>

<!-- Location Block -->
<?php
$this->setModel('Location', $event->location);
echo $this->loadAnyTemplate('site:com_sdajem/Locations/location');
?>

<!-- Attendee Block -->
<?php
if ((bool) $event->useRegistration)
{
	echo $this->loadAnyTemplate('site:com_sdajem/Events/attendees');
}
?>

<!-- Comment Block -->
<?php
if (!$guest)
{
	echo $this->loadAnyTemplate('site:com_sdajem/Events/comments');
}
?>

</div>
