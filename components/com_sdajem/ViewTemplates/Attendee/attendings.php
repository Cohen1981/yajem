<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;

/** @var \Sda\Profiles\Site\Model\Profile $profile */
/** @var \Joomla\Input\Input $input */
/** @var \Sda\Jem\Site\Model\Attendee $attendee */

$this->addCssFile('media://com_sdajem/css/style.css');

$input = $this->input->getArray();
if ($input['option'] == 'com_sdaprofiles' && $input['task'] == 'edit')
{
	$profile = $this->getModel();
}
?>

<div class="well">
	<div class="titleContainer">
		<h2 class="page-title">
			<span class="icon-generic" aria-hidden="true"></span>
			<?php echo Text::_('COM_SDAJEM_TITLE_ATTENDINGS'); ?>
		</h2>
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
			<div id="sdajem_attending<?php echo $attendee->sdajem_attendee_id; ?>" class="control-group">
				<label class="control-label">

				</label>
				<div class="controls">
					<?php
					$eventTitle = "<span class=\"sdajem_attending_cell\">" .
						"<a href=\"index.php?option=com_sdajem&view=Events&task=read&id=" . $attendee->event->sdajem_event_id . "\">" .
						$attendee->event->title .
						"</a>" .
						"</span>";
					switch ($attendee->status)
					{
						case 0:
							echo $eventTitle;
							echo "<span class=\"sdajem_status_label sdajem_grey\">" . Text::_('COM_SDAJEM_UNDECIDED') . "</span>";
							break;
						case 1:
							echo $eventTitle;
							echo "<span class=\"sdajem_status_label sdajem_green\">" . Text::_('COM_SDAJEM_ATTENDING') . "</span>";
							break;
					}
					?>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
