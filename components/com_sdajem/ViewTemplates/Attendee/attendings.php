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
use Joomla\CMS\Factory;

/** @var \Sda\Profiles\Site\Model\Profile $profile */
/** @var \Joomla\Input\Input $input */
/** @var \Sda\Jem\Site\Model\Attendee $attendee */
/** @var \Sda\Jem\Site\View\Attendee\Raw $this */

$this->addCssFile('media://com_sdajem/css/style.css');
$this->addJavascriptFile('media://com_sdajem/js/attendings.js');

$input = $this->input->getArray();
if ($input['option'] == 'com_sdaprofiles')
{
	$profile = $this->getModel();
}

// This ViewTemplate could be called and displayed by other Components so we load the language Strings.
$language    = Factory::getLanguage();
$extension   = 'com_sdajem';
$languageTag = $language->getTag();
$baseDir     = JPATH_ROOT . "/components/" . $extension;
$language->load($extension, $baseDir, $languageTag, true);
?>

<div class="well">
	<div class="titleContainer">
		<h2 class="page-title">
			<span class="icon-generic" aria-hidden="true"></span>
			<?php echo Text::_('COM_SDAJEM_TITLE_ATTENDINGS'); ?>
		</h2>
	</div>
	<div id="attending_filter_buttons" class="buttonsContainer">
		<label><?php echo Text::_('SDAJEM_FILTER_ATTENDINGS'); ?></label>
		<input type="radio" id="attending" name="status" value="attending" checked onchange="filterStatus('attending')">
		<label for="attending"> <?php echo Text::_('COM_SDAJEM_ATTENDING'); ?></label>
		<input type="radio" id="all" name="status" value="all" onchange="filterStatus('all')">
		<label for="all"> <?php echo Text::_('COM_SDAJEM_ALL'); ?></label>
		<input type="radio" id="nattending" name="status" value="nattending" onchange="filterStatus('nattending')">
		<label for="nattending"> <?php echo Text::_('COM_SDAJEM_NATTENDING'); ?></label>
	</div>
</div>

<div id="attending_area" class="form-horizontal">
	<div id="sdajem_attendings" class="control-group">
		<label class="control-label">
		</label>
		<div class="controls">
			<span class="sdajem_attending_cell"><?php echo Text::_('COM_SDAJEM_EVENT_TITLE_LABEL'); ?></span>
			<span class="sdajem_attending_cell"><?php echo Text::_('COM_SDAJEM_ATTENDEE_STATUS_LABEL'); ?></span>
		</div>
	</div>
	<?php if ($profile->attendees) : ?>
		<?php foreach ($profile->attendees as $attendee) : ?>
		<?php if ($attendee->event->enabled == 1) : ?>
			<?php
			$eventTitle = "<span class=\"sdajem_attending_cell\">" .
				"<a href=\"index.php?option=com_sdajem&view=Events&task=read&id=" . $attendee->event->sdajem_event_id . "\">" .
				$attendee->event->title .
				"</a>" .
				"</span>";

			switch ($attendee->status)
			{
				case 0:
					$statusLabel = "<span class=\"sdajem_status_label sdajem_grey\">" . Text::_('COM_SDAJEM_UNDECIDED') . "</span>";
					$class = "sdajem_undecided";
					$hidden = "hidden";
					break;
				case 1:
					$statusLabel = "<span class=\"sdajem_status_label sdajem_green\">" . Text::_('COM_SDAJEM_ATTENDING') . "</span>";
					$class = "sdajem_attending";
					$hidden = "";
					break;
				case 2:
					$statusLabel = "<span class=\"sdajem_status_label sdajem_red\">" . Text::_('COM_SDAJEM_NATTENDING') . "</span>";
					$class = "sdajem_nattending";
					$hidden = "hidden";
					break;
			}
			?>

			<div id="sdajem_attending<?php echo $attendee->sdajem_attendee_id; ?>" <?php echo $hidden;?>
			     class="sdajem_attendings control-group <?php echo $class; ?>">

				<label class="control-label">
					<?php
					echo $attendee->event->getFormatedStartDate() . " - ";
					echo $attendee->event->getFormatedEndDate();
					?>
				</label>
				<div class="controls">
					<?php echo $eventTitle; ?>
					<?php echo $statusLabel; ?>
				</div>

			</div>
		<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
