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
$this->addJavascriptFile('media://com_sdajem/js/eventItem.js');
$event = $this->getItem();

$guest = Factory::getUser()->guest;
?>
<div class="sdajem_tabs">
	<label id="event_switch_label" class="sdajem_tab sda_active" for="event_switch">
		<?php echo Text::_('COM_SDAJEM_TITLE_EVENT_BASIC') ?>
	</label>
	<label id="location_switch_label" class="sdajem_tab" for="location_switch">
		<?php echo Text::_('COM_SDAJEM_TITLE_LOCATION_BASIC') ?>
	</label>
	<label id="attendees_switch_label" class="sdajem_tab" for="attendees_switch">
		<?php echo Text::_('COM_SDAJEM_TITLE_ATTENDEES_BASIC') ?>
	</label>
	<label id="comments_switch_label" class="sdajem_tab" for="comments_switch">
		<?php echo Text::_('COM_SDAJEM_TITLE_COMMENTS_BASIC') ?>
	</label>
</div>

<div class="sdajem_content_container">

	<form action="<?php echo JRoute::_('index.php?option=com_sdajem&view=Events'); ?>" method="post"
	      name="adminForm" id="adminForm">
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="id" value="<?php echo $event->sdajem_event_id; ?>">
		<?php echo JHtml::_('form.token'); ?>
	</form>

	<div>
		<input type="checkbox" id="event_switch" class="sdaprofiles_hidden" hidden checked="checked" onchange="switchCheckBox('event_switch')"/>
		<div class="sdajem_switchable">
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

				<?php if ($event->host): ?>
				<div class="sdajem_label">
					<?php echo Text::_('COM_SDAJEM_EVENT_HOST'); ?>
				</div>
				<div class="sdajem_value">
					<?php echo $event->host->getLinkToContact(); ?>
				</div>
				<?php endif; ?>

				<?php if ($event->organizer): ?>
					<div class="sdajem_label">
						<?php echo Text::_('COM_SDAJEM_EVENT_ORGANIZERID_LABEL'); ?>
					</div>
					<div class="sdajem_value">
						<?php echo $event->organizer->getLinkToContact(); ?>
					</div>
				<?php endif; ?>

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
		</div>
	</div>

	<!-- Location Block -->
	<?php if ($event->location) : ?>
	<div>
		<input type="checkbox" 
		       id="location_switch" 
		       class="sdaprofiles_hidden" 
		       hidden
		       onchange="switchCheckBox('location_switch')"
		/>
		<div class="sdajem_switchable">
			<?php $this->setModel('Location', $event->location); ?>
			<?php echo $this->loadAnyTemplate('site:com_sdajem/Locations/location'); ?>
		</div>
	</div>
	<?php endif; ?>

	<!-- Attendee Block -->
	<?php if ((bool) $event->useRegistration) : ?>
	<div>
		<input 
				type="checkbox" 
				id="attendees_switch" 
				class="sdaprofiles_hidden" 
				hidden 
				onchange="switchCheckBox('attendees_switch')"
		/>
		<div class="sdajem_switchable">
			<?php echo $this->loadAnyTemplate('site:com_sdajem/Events/attendees'); ?>
		</div>
	</div>
	<?php endif; ?>

	<!-- Comment Block -->
	<?php if (!$guest) : ?>
	<div>
		<input 
				type="checkbox" 
				id="comments_switch" 
				class="sdaprofiles_hidden" 
				hidden
				onchange="switchCheckBox('comments_switch')"
		/>
		<div class="sdajem_switchable">
			<?php echo $this->loadAnyTemplate('site:com_sdajem/Events/comments'); ?>
		</div>
	</div>
	<?php endif; ?>

</div>
