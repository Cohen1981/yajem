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
use Joomla\CMS\Language\Text;

/** @var \Sda\Jem\Site\View\Event\Html  $this       */
/** @var \Sda\Jem\Site\Model\Event      $event      */
/** @var \Sda\Jem\Site\Model\Attendee   $attendee   */
/** @var \Sda\Jem\Site\Model\Comment    $comment    */

$this->addCssFile('media://com_sdajem/css/style.css');
$event = $this->getItem();

$guest = Factory::getUser()->guest;
?>
<form action="<?php echo JRoute::_('index.php?option=com_sdajem&view=Events'); ?>" method="post"
      name="adminForm" id="adminForm">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="id" value="<?php echo $event->sdajem_event_id; ?>">
	<?php echo JHtml::_('form.token'); ?>
</form>

<div class="sdajem_content_container">

	<div class="sdajem_event_table">

	<div class="sdajem_event_grid">

		<?php if ($event->organizerId != 0) : ?>

		<div class="sdajem_label">
			<h3>
				<?php echo Text::_('COM_SDAJEM_EVENT_EVENTSTATUS_LABEL'); ?>
			</h3>
		</div>
		<div id="eventStatus" class="sdajem_value">
			<?php echo $this->loadAnyTemplate('site:com_sdajem/Event/eventStatus'); ?>
		</div>

		<?php endif; ?>

		<div class="sdajem_label">
			<?php echo Text::_('COM_SDAJEM_EVENT_CATEGORY_LABEL'); ?>
		</div>
		<div class="sdajem_value">
			<?php echo $event->category->title; ?>
		</div>


		<div class="sdajem_label">
			<b>
				<?php echo Text::_('COM_SDAJEM_EVENT_STARTDATETIME_LABEL'); ?>
			</b>
		</div>
		<div class="sdajem_value">
			<b>
				<?php echo $event->getFormattedStartDate(); ?>
			</b>
		</div>
		<div class="sdajem_label">
			<b>
				<?php echo Text::_('COM_SDAJEM_EVENT_ENDDATETIME_LABEL'); ?>
			</b>
		</div>
		<div class="sdajem_value">
			<b>
				<?php echo $event->getFormattedEndDate(); ?>
			</b>
		</div>

		<div class="sdajem_label">
			<?php echo Text::_('COM_SDAJEM_EVENT_URL_LABEL'); ?>
		</div>
		<div class="sdajem_value">
			<a href="<?php echo $event->url; ?>" target="_blank"><?php echo $event->url; ?></a>
		</div>
		<div class="sdajem_label">
			<?php echo Text::_('COM_SDAJEM_EVENT_HOST'); ?>
		</div>
		<div class="sdajem_value">
			<?php echo $event->host->getLinkToContact(); ?>
		</div>
		<div class="sdajem_label">
			<?php echo Text::_('COM_SDAJEM_EVENT_DESCRIPTION_LABEL'); ?>
		</div>
		<div class="sdajem_value">
			<?php echo $event->description; ?>
		</div>

	</div>

	<?php if ($event->image) :?>
		<div class="sdajem_image_container">
			<img src="<?php echo $event->image ?>" />
		</div>
	<?php endif; ?>

	</div>

<!-- Location Block -->
<?php
if ($event->location)
{
	$this->setModel('Location', $event->location);
	echo $this->loadAnyTemplate('site:com_sdajem/Locations/location');
}
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
