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

$this->addCssFile('media://com_sdajem/css/sdajem_style.css');
$this->addJavascriptFile('media://com_sdajem/js/eventItem.js');
$this->addJavascriptFile('media://com_sda/js/jquery-3.3.1.min.js');
$this->addJavascriptFile('media://com_sda/js/tabSwitch.js');

$event = $this->getItem();

$guest = Factory::getUser()->guest;
?>
	<label id="event_switch_label" class="sda_tab sda_active" for="event_switch">
		<?php echo IconHelper::titleIcon() . " " . Text::_('COM_SDAJEM_TITLE_EVENT_BASIC') ?>
		<span id="event_switch_state" class="fas fa-angle-double-up sdajem_float_right"></span>
	</label>

<div class="sdajem_content_container">

	<form action="<?php echo JRoute::_('index.php?option=com_sdajem&view=Events'); ?>" method="post"
	      name="adminForm" id="adminForm">
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="id" value="<?php echo $event->sdajem_event_id; ?>">
		<?php echo JHtml::_('form.token'); ?>
	</form>

	<div>
		<input type="checkbox" id="event_switch" class="sdaprofiles_hidden sda_switchinputbox" hidden checked="checked"/>
		<div id="event_switch_Tab" class="sda_switchable">
			<div class="sdajem_event_table">

			<div class="sdajem_event_grid">

				<?php if ($event->organizerId != 0 && !$guest) : ?>

				<div class="sdajem_label">
					<h3>
						<?php echo Text::_('COM_SDAJEM_EVENT_EVENTSTATUS_LABEL'); ?>
					</h3>
				</div>
				<div id="eventStatus_<?php echo $event->sdajem_event_id ?>" class="sdajem_value">
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
					<img class="sdajem_event_image" src="<?php echo $event->image ?>" />
				</div>
			<?php endif; ?>

			</div>
		</div>
	</div>

	<!-- Location Block -->
	<?php if ($event->location) : ?>
        <label id="location_switch_label" class="sda_tab" for="location_switch">
			<?php echo IconHelper::locationIcon() . " " . Text::_('COM_SDAJEM_TITLE_LOCATION_BASIC') ?>
	        <span id="location_switch_state" class="fas fa-angle-double-down sdajem_float_right"></span>
        </label>
	<?php endif; ?>
	<?php if ($event->location) : ?>
	<div>
		<input type="checkbox" 
		       id="location_switch" 
		       class="sdaprofiles_hidden sda_switchinputbox"
		       hidden
		/>
		<div id="location_switch_Tab" class="sda_switchable">
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
	<?php if ((bool) $event->useRegistration && !$guest) : ?>
        <label id="attendees_switch_label" class="sda_tab" for="attendees_switch">
			<?php echo IconHelper::usersIcon() . " " . Text::_('COM_SDAJEM_TITLE_ATTENDEES_BASIC') ?>
	        (<span id="attendeeCounter"><?php if ($event->getAttendingCount() > 0) { echo $event->getAttendingCount(); } else { echo 0;} ?></span>)
	        <span id="attendees_switch_reload" hidden><i class="fas fa-spinner fa-pulse"></i></span>
	        <span id="attendees_switch_state" class="fas fa-angle-double-down sdajem_float_right"></span>
        </label>
	<?php endif; ?>
	<?php if ((bool) $event->useRegistration) : ?>
	<div>
		<input 
				type="checkbox" 
				id="attendees_switch"
				class="sdaprofiles_hidden sda_switchinputbox"
				hidden
		/>
		<div id="attendees_switch_Tab" class="sda_switchable">
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
        <label id="comments_switch_label" class="sda_tab" for="comments_switch" onclick="commentRead(<?php echo Factory::getUser()->id ?>)">
			<?php echo IconHelper::commentsIcon() . " " . Text::_('COM_SDAJEM_TITLE_COMMENTS_BASIC'); ?>
			<?php
				if ($event->hasUnreadComments(Factory::getUser()->id)) {
					$class = 'sdajem_text_red';
				}
				echo "(<span id='sdaCommentCount' class=\"$class\">" . $event->comments->count() . "</span>)";
			?>
            <span id="comments_switch_reload" hidden><i class="fas fa-spinner fa-pulse"></i></span>
	        <span id="comments_switch_state" class="fas fa-angle-double-down sdajem_float_right"></span>
        </label>
	<?php endif; ?>
	<?php if (!$guest) : ?>
	<div>
		<input 
				type="checkbox" 
				id="comments_switch" 
				class="sdaprofiles_hidden sda_switchinputbox"
				hidden
		/>
		<div id="comments_switch_Tab" class="sda_switchable">
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
        <label id="planing_switch_label" class="sda_tab" for="planing_switch">
			<?php echo IconHelper::planingIcon() . " " . Text::_('COM_SDAJEM_TITLE_PLANER_BASIC') ?>
	        <span id="planing_switch_reload" hidden><i class="fas fa-spinner fa-pulse"></i></span>
	        <span id="planing_switch_state" class="fas fa-angle-double-down sdajem_float_right"></span>
        </label>
	<?php endif; ?>
	<?php if((bool) ComponentHelper::getParams('com_sdajem')->get('usePlaningTool') && !$guest && (bool) $event->useFittings) : ?>
	<div>
		<input
				type="checkbox"
				id="planing_switch"
				class="sdaprofiles_hidden sda_switchinputbox"
				hidden
		/>
		<div id="planing_switch_Tab" class="sda_switchable">

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
