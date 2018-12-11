<?php
/**
 * @package     Yajem.Site
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Joomla\CMS\Factory;

$user       = Factory::getUser();
$userId     = $user->get('id');
$allDay = (bool) $this->event->allDayEvent;

$useOrga = (bool) JComponentHelper::getParams('com_yajem')->get('use_organizer');

if ($useOrga)
{
	$symbolConfirmed = '<i class="far fa-thumbs-up" aria-hidden="true" title="' . JText::_("COM_YAJEM_EVENT_STATUS_CONFIRMED") . '"></i>';
	$symbolCanceled = '<i class="far fa-thumbs-down" aria-hidden="true" title="' . JText::_("COM_YAJEM_EVENT_STATUS_CANCELED") . '"></i>';
	$linkConfirm = '<label id="yajem_confirm" class="yajem_css_switch yajem_rounded" for="eConfirm">' . $symbolConfirmed . '</label>';
	$linkCancel = '<label id="yajem_canc" class="yajem_css_switch yajem_rounded" for="eCancel">' . $symbolCanceled . '</label>';
}
?>

<div class="yajem_image">
	<?php
	$this->event->img = new Registry;
	$this->event->img->loadString($this->event->image);

	$caption = $this->event->img->get('caption') ? : '';
	$src = JURI::root() . ($this->event->img->get('image') ? : '' );
	?>

	<img src="<?php echo $src; ?>" title="<?php echo $caption; ?>" />
</div>

<div id="event_basics" class="yajem_grid_section">
	<?php if ($userId == $this->event->organizer->user_id): ?>

		<div class="yajem_full_grid_row">
			<form action="<?php echo JRoute::_('index.php?option=com_yajem&view=events'); ?>"  name="eventForm" id="eventForm" method="post">
				<div class="yajem_flex_row" id="eventStatus">
					<?php
					switch ($this->event->eventStatus)
					{
						case 0:
							echo $linkConfirm;
							echo $linkCancel;
							break;
						case 1:
							echo $linkCancel;
							break;
						case 2:
							echo $linkConfirm;
							break;
					}
					?>
					<input type="radio" class="yajem_hidden" id="eConfirm" name="eStatus" value="confirm" onchange="eventForm.submit()" />
					<input type="radio" class="yajem_hidden" id="eCancel" name="eStatus" value="cancel" onchange="eventForm.submit()" />
					<input type="hidden" name="eventId" value="<?php echo $this->event->id; ?>" />
					<input type="hidden" name="task" value="event.changeEventStatus" />
					<?php echo JHtml::_( 'form.token' ); ?>
				</div>
			</form>
		</div>

	<?php endif; ?>

	<div class="yajem_label">
		<?php echo JText::_('COM_YAJEM_EVENT_STARTDATE_LABEL'); ?>
	</div>
	<div class="yajem_output">
		<?php
		if ($allDay)
		{
			echo date('d.m.Y', strtotime($this->event->startDate));
		}
		else
		{
			echo date('d.m.y H:i', strtotime($this->event->startDateTime));
		}
		?>
	</div>

	<div class="yajem_label">
		<?php echo JText::_('COM_YAJEM_EVENT_ENDDATE_LABEL'); ?>
	</div>
	<div class="yajem_output">
		<?php
		if ($allDay)
		{
			echo date('d.m.Y', strtotime($this->event->endDate));
		}
		else
		{
			echo date('d.m.y H:i', strtotime($this->event->endDateTime));
		}
		?>
	</div>

	<?php if ($this->event->hostLink): ?>
		<div class="yajem_label">
			<?php echo JText::_('COM_YAJEM_EVENT_HOST_LABEL'); ?>
		</div>
		<div class="yajem_output">
			<?php echo $this->event->hostLink; ?>
		</div>
	<?php endif; ?>

	<?php if ($this->event->description): ?>
		<div class="yajem_label">
			<?php echo JText::_('COM_YAJEM_EVENT_DESCRIPTION_LABEL'); ?>
		</div>
		<div class="yajem_output">
			<?php echo $this->event->description; ?>
		</div>
	<?php endif; ?>
</div>
