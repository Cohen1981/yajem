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
use Joomla\CMS\Component\ComponentHelper;
use Sda\Jem\Admin\Helper\IconHelper;

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
		<?php echo IconHelper::titleIcon() . " " . Text::_('COM_SDAJEM_TITLE_EVENT_BASIC') ?>
	</label>
	<?php if ($event->location) : ?>
	<label id="location_switch_label" class="sdajem_tab" for="location_switch">
		<?php echo IconHelper::locationIcon() . " " . Text::_('COM_SDAJEM_TITLE_LOCATION_BASIC') ?>
	</label>
	<?php endif; ?>
	<?php if ((bool) $event->useRegistration && !$guest) : ?>
	<label id="attendees_switch_label" class="sdajem_tab" for="attendees_switch">
		<?php echo IconHelper::usersIcon() . " " . Text::_('COM_SDAJEM_TITLE_ATTENDEES_BASIC') ?>
		<?php if ($event->getAttendingCount() > 0) { echo "(" . $event->getAttendingCount() . ")"; } ?>
	</label>
	<?php endif; ?>
	<?php if (!$guest) : ?>
	<label id="comments_switch_label" class="sdajem_tab" for="comments_switch">
		<?php echo IconHelper::commentsIcon() . " " . Text::_('COM_SDAJEM_TITLE_COMMENTS_BASIC'); ?>
		<?php if ($event->comments->count() > 0) { echo " (" . $event->comments->count() . ")"; } ?>
	</label>
	<?php endif; ?>
	<?php if((bool) ComponentHelper::getParams('com_sdajem')->get('usePlaningTool') && !$guest && (bool) $event->useFittings) : ?>
	<label id="planing_switch_label" class="sdajem_tab" for="planing_switch">
		<?php echo IconHelper::planingIcon() . " " . Text::_('COM_SDAJEM_TITLE_PLANER_BASIC') ?>
	</label>
	<?php endif; ?>
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

				<?php if ($event->organizerId != 0 && !$guest) : ?>

				<div class="sdajem_label">
					<h3>
						<?php echo Text::_('COM_SDAJEM_EVENT_EVENTSTATUS_LABEL'); ?>
					</h3>
				</div>
				<div id="eventStatus" class="sdajem_value">
					<?php
					try
					{
						echo $this->loadAnyTemplate('site:com_sdajem/Event/eventStatus');
					}
					catch (Exception $e)
					{
					}
					?>
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
						<?php echo $event->getFormatedStartDate(); ?>
					</b>
				</div>
				<div class="sdajem_label">
					<b>
						<?php echo Text::_('COM_SDAJEM_EVENT_ENDDATETIME_LABEL'); ?>
					</b>
				</div>
				<div class="sdajem_value">
					<b>
						<?php echo $event->getFormatedEndDate(); ?>
					</b>
				</div>

				<?php if ($event->url): ?>
					<div class="sdajem_label">
						<?php echo Text::_('COM_SDAJEM_EVENT_URL_LABEL'); ?>
					</div>
					<div class="sdajem_value">
						<a href="<?php echo $event->url; ?>" target="_blank"><?php echo $event->url; ?></a>
					</div>
				<?php endif; ?>

				<?php if ($event->host): ?>
				<div class="sdajem_label">
					<?php echo Text::_('COM_SDAJEM_EVENT_HOST'); ?>
				</div>
				<div class="sdajem_value">
					<?php echo $event->host->getLinkToContact(); ?>
				</div>
				<?php endif; ?>

				<?php if ($event->organizer && !$guest): ?>
					<div class="sdajem_label">
						<?php echo Text::_('COM_SDAJEM_EVENT_ORGANIZERID_LABEL'); ?>
					</div>
					<div class="sdajem_value">
						<?php echo $event->organizer->getLinkToContact(); ?>
					</div>
				<?php endif; ?>
				
				<?php if ($event->description): ?>
					<div class="sdajem_label">
						<?php echo Text::_('COM_SDAJEM_EVENT_DESCRIPTION_LABEL'); ?>
					</div>
					<div class="sdajem_value">
						<?php echo $event->description; ?>
					</div>
				<?php endif; ?>

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
			<?php
			try
			{
				echo $this->loadAnyTemplate('site:com_sdajem/Locations/location');
			}
			catch (Exception $e)
			{
			}
			?>
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
		<div id="attending_block" class="sdajem_switchable">
			<?php
			try
			{
				echo $this->loadAnyTemplate('site:com_sdajem/Event/attendees');
			}
			catch (Exception $e)
			{
			}
			?>
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
			<?php
			try
			{
				echo $this->loadAnyTemplate('site:com_sdajem/Events/comments');
			}
			catch (Exception $e)
			{
			}
			?>
		</div>
	</div>
	<?php endif; ?>

	<?php if((bool) ComponentHelper::getParams('com_sdajem')->get('usePlaningTool') && !$guest && (bool) $event->useFittings) : ?>
	<div>
		<input
				type="checkbox"
				id="planing_switch"
				class="sdaprofiles_hidden"
				hidden
				onchange="switchCheckBox('planing_switch')"
		/>
		<div id="planingTab" class="sdajem_switchable">

			<?php
			try
			{
				echo $this->loadAnyTemplate('site:com_sdajem/Event/planingTool');
			}
			catch (Exception $e)
			{
			}
			?>

		</div>
	</div>
	<?php endif; ?>

</div>
