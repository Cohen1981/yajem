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

JHtml::_('stylesheet', JUri::root() . 'media/com_yajem/css/style.css');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user       = JFactory::getUser();
$guest		= $user->guest;
$userId     = $user->get('id');
$listOrder 	= $this->escape($this->state->get('list.ordering'));
$listDirn  	= $this->escape($this->state->get('list.direction'));
$canCreate  = $user->authorise('core.create', 'com_yajem');
$canEdit    = $user->authorise('core.edit.state', 'com_yajem');
$canDelete  = $user->authorise('core.delete', 'com_yajem');
$pastEvents = (bool) JComponentHelper::getParams('com_yajem')->get('show_pastEvents');

$symbolOpen = '<i class="fas fa-question-circle" aria-hidden="true" title="' . JText::_("COM_YAJEM_EVENT_STATUS_OPEN") . '">&nbsp;</i>';
$symbolConfirmed = '<i class="far fa-thumbs-up" aria-hidden="true" title="' . JText::_("COM_YAJEM_EVENT_STATUS_CONFIRMED") . '">&nbsp;</i>';
$symbolCanceled = '<i class="far fa-thumbs-down" aria-hidden="true" title="' . JText::_("COM_YAJEM_EVENT_STATUS_CANCELED") . '">&nbsp;</i>';

$currentDate 	= Factory::getDate('now', 'UTC');
?>

<script language="javascript" type="text/javascript">
	function tableOrdering( order, dir, task )
	{
		var form = document.adminForm;

		form.filter_order.value = order;
		form.filter_order_Dir.value = dir;
		document.adminForm.submit( task );
	}
</script>


<form action="<?php echo JRoute::_('index.php?option=com_yajem&view=events'); ?>" method="post"
	  name="adminForm" id="adminForm">

<div id="yajem_container">
	<?php
	echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>

    <?php if ($canCreate) : ?>
    <div class="yajem_section_container">
        <a href="<?php echo JRoute::_('index.php?option=com_yajem&task=editevent.add'); ?>">
		    <i class="fas fa-calendar-plus" aria-hidden="true"></i>
	        <?php echo Text::_('COM_YAJEM_NEW_EVENT'); ?>
        </a>&nbsp;
        <a href="<?php echo JRoute::_('index.php?option=com_yajem&view=locations'); ?>">
            <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
	        <?php echo Text::_('COM_YAJEM_MANAGE_LOCATIONS'); ?>
        </a>
    </div>
    <?php endif; ?>

	<div class="yajem_section_container">
		<div class="yajem_flex_row">
			<div class="yajem_cell yajem_head">
				<i class="far fa-calendar-alt" aria-hidden="true" title="<?php echo JText::_('COM_YAJEM_ICON_DATE') ?>"></i>
				<?php echo JHtml::_('grid.sort', 'COM_YAJEM_EVENT_STARTDATE_TITLE', 'a.startDate', $listDirn, $listOrder); ?> -
				<?php echo JHtml::_('grid.sort', 'COM_YAJEM_EVENT_ENDDATE_TITLE', 'a.endDate', $listDirn, $listOrder); ?>
			</div>
			<div class="yajem_cell yajem_head">
				<i class="far fa-bookmark" aria-hidden="true" title="<?php echo JText::_('COM_YAJEM_ICON_TITLE') ?>">&nbsp;</i>
				<?php echo JHTML::_( 'grid.sort', 'COM_YAJEM_EVENT_EVENT_TITLE', 'a.title', $listDirn, $listOrder); ?>
			</div>
			<div class="yajem_cell yajem_head">
				<i class="fas fa-map-marker-alt" aria-hidden="true" title="<?php echo JText::_('COM_YAJEM_ICON_MAP') ?>">&nbsp;</i>
				<?php echo JHtml::_('grid.sort', 'COM_YAJEM_EVENT_LOCATION_TITLE', 'location', $listDirn, $listOrder); ?>
			</div>
			<div class="yajem_cell yajem_head">
				<i class="far fa-flag" aria-hidden="true" title="<?php echo JText::_('COM_YAJEM_ICON_CATEGORY') ?>"></i>
				<?php echo JHtml::_('grid.sort', 'COM_YAJEM_EVENT_CATEGORY_TITLE', 'cat_title', $listDirn, $listOrder); ?>
			</div>
			<div class="yajem_cell yajem_head">
				<i class="fas fa-users" aria-hidden="true" title="<?php echo JText::_('COM_YAJEM_ICON_USERS') ?>"></i>
				<?php echo JHtml::_('grid.sort', 'COM_YAJEM_EVENT_ATTENDEES_TITLE', 'attendees', $listDirn, $listOrder); ?>
			</div>

			<?php if (!$guest):	?>
				<div class="yajem_cell yajem_head">
					<i class="fas fa-info-circle" aria-hidden="true" title="<?php echo JText::_('COM_YAJEM_ICON_STATUS') ?>">&nbsp;</i>
					<?php echo JText::_('COM_YAJEM_EVENT_STATUS'); ?>
				</div>
			<?php endif; ?>

		</div>
	</div>

	<?php foreach ($this->items as $i => $item) : ?>

	<?php $start = (bool) $item->allDayEvent ? Factory::getDate($item->startDate, 'UTC') : Factory::getDate($item->startDateTime, 'UTC');?>
	<?php if ($currentDate <= $start || $pastEvents): ?>
	<div class="yajem_section_container">
		<div class="yajem_flex_row">
			<div class="yajem_cell">
				<a onclick="getIcs(<?php echo $item->id; ?>)"
                   class="hasPopover"
                   data-content="<?php echo Text::_('COM_YAJEM_ICS_DOWNLOAD_DESC'); ?>"
                   data-original-title="<?php echo Text::_('COM_YAJEM_ICS_DOWNLOAD'); ?>">
                    <i class="far fa-calendar-alt" aria-hidden="true"></i>
                </a>
				<?php
				if ((bool) $item->allDayEvent)
				{
					echo date('d.m.Y', strtotime($item->startDate)) . ' - ';
					echo date('d.m.Y', strtotime($item->endDate));
				}
				else
				{
					echo date('d.m.Y H:i', strtotime($item->startDateTime)) . ' - ';
					echo date('d.m.Y H:i', strtotime($item->endDateTime));
				}
				?>
			</div>
			<div class="yajem_cell">
				<i class="far fa-bookmark" aria-hidden="true">&nbsp;</i>
				<a href="<?php echo JRoute::_('index.php?option=com_yajem&task=event.view&id=' . (int) $item->id); ?>">
					<?php echo $this->escape($item->title); ?>
				</a>
			</div>
			<div class="yajem_cell">
				<i class="fas fa-map-marker-alt" aria-hidden="true"></i>
				<?php echo $this->escape($item->location); ?>
			</div>
			<div class="yajem_cell">
				<i class="far fa-flag" aria-hidden="true"></i>
				<?php echo $this->escape($item->cat_title); ?>
			</div>
			<div class="yajem_cell">
				<i class="fas fa-users" aria-hidden="true"></i>
				<?php echo $this->escape($item->attendees); ?>
			</div>

			<?php
				if (!$guest)
				{
					echo '<div class="yajem_cell">';
					if ((bool)$item->useOrganizer)
					{
						switch ($item->eventStatus)
						{
							case 0:
								echo $symbolOpen;
								break;
							case 1:
								echo $symbolConfirmed;
								break;
							case 2:
								echo $symbolCanceled;
								break;
						}
					}
					echo '</div>';
				}
			?>

		</div>
	</div>
	<?php endif; ?>
	<?php endforeach; ?>
</div>
	<?php echo $this->pagination->getListFooter(); ?>

	<input type="hidden" name="filter_order" value="<?php echo $this->list['ordering']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->list['direction']; ?>" />
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>
