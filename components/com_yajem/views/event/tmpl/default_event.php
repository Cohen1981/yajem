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
$guest		= $user->guest;
$userId     = $user->get('id');
$allDay = (bool) $this->event->allDayEvent;
$useHost = (bool) JComponentHelper::getParams('com_yajem')->get('use_host');
if ($useHost)
{
	$useHost = (bool) $this->event->useOrganizer;
}
$useOrga = (bool) JComponentHelper::getParams('com_yajem')->get('use_organizer');
if ($useOrga)
{
	$useOrga = (bool) $this->event->useOrganizer;
}

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

	<?php if ($useHost): ?>
		<div class="yajem_label">
			<?php echo JText::_('COM_YAJEM_EVENT_HOST_LABEL'); ?>
		</div>
		<div class="yajem_output">
			<?php echo $this->event->hostLink; ?>
		</div>
	<?php endif; ?>

	<?php if ($useOrga && !$guest): ?>
        <div class="yajem_label"><?php echo JText::_('COM_YAJEM_ORGANIZER_LABEL'); ?></div>
        <div class="yajem_output"><?php echo $this->event->organizer['name']; ?></div>
	<?php endif; ?>

	<?php if ($this->event->description): ?>
		<div class="yajem_label">
			<?php echo JText::_('COM_YAJEM_EVENT_DESCRIPTION_LABEL'); ?>
		</div>
		<div class="yajem_output">
			<?php echo $this->event->description; ?>
		</div>
	<?php endif; ?>
    <div class="yajem_label">
            <?php echo JText::_('COM_YAJEM_ATTACHMENTS_LABEL');?>
    </div>
    <div class="yajem_output">
	    <?php foreach ($this->event->attachments as $a => $attachment) : ?>
        <div class="yajem_flex_row">
            <div>
                <?php
                    $src = JURI::root() . $attachment->file;
                ?>
                <a href="<?php echo $src ?>" target="_blank"><i class="fas fa-paperclip" aria-hidden="true">&nbsp;</i><?php echo $attachment->title ?></a>
            </div>
        </div>
	    <?php endforeach; ?>
    </div>

</div>
