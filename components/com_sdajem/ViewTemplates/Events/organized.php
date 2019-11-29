<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;
use FOF30\Date\Date;
use Sda\Jem\Admin\Helper\EventStatusHelper;

/** @var \Sda\Profiles\Site\Model\Profile $profile */
/** @var \Joomla\Input\Input $input */
/** @var \Sda\Jem\Site\Model\Event $event */
/** @var \Sda\Jem\Site\View\Attendee\Raw $this */

$this->addCssFile('media://com_sdajem/css/style.css');

$input = $this->input->getArray();
if ($input['option'] == 'com_sdaprofiles')
{
	$profile = $this->getModel();
}

$currentDate = new DateTime;
?>

<div class="well">
	<div class="titleContainer">
		<h2 class="page-title">
			<span class="icon-generic" aria-hidden="true"></span>
			<?php echo Text::_('COM_SDAJEM_TITLE_ORGANIZING'); ?>
		</h2>
	</div>
</div>
<div id="attending_area" class="form-horizontal">
	<div id="sdajem_attendings" class="control-group">
		<label class="control-label">
		</label>
		<div class="controls">
			<span class="sdajem_attending_cell"><?php echo Text::_('COM_SDAJEM_EVENT_TITLE_LABEL'); ?></span>
			<span class="sdajem_attending_cell"><?php echo Text::_('COM_SDAJEM_ATTENDING_COUNT'); ?></span>
			<span class="sdajem_attending_cell"><?php echo Text::_('COM_SDAJEM_SPACE_REQUIRED'); ?></span>
			<span class="sdajem_attending_cell"><?php echo Text::_('COM_SDAJEM_ATTENDEE_STATUS_LABEL'); ?></span>
		</div>
	</div>
	<?php foreach ($profile->organizing as $event) : ?>
		<?php $startDT = new DateTime($event->startDateTime); ?>
		<?php if ($startDT >= $currentDate): ?>
		<div id="sdajem_organized<?php echo $event->sdajem_event_id; ?>" class="control-group">
			<label class="control-label">
				<?php
				echo $event->getFormatedStartDate() . " - ";
				echo $event->getFormatedEndDate();
				?>
			</label>
			<div class="controls">
				<?php
				$eventTitle = "<span class=\"sdajem_attending_cell\">" .
					"<a href=\"index.php?option=com_sdajem&view=Events&task=read&id=" . $event->sdajem_event_id . "\">" .
					$event->title .
					"</a>" .
					"</span>";
				echo $eventTitle;
				echo "<span class=\"sdajem_attending_cell\">" . $event->getAttendingCount() . "</span>";
				echo "<span class=\"sdajem_attending_cell\">" . $event->getRequiredSpaceForEvent() . "</span>";
				echo EventStatusHelper::getSymbolByStatus($event->eventStatus);
				?>
			</div>
		</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
