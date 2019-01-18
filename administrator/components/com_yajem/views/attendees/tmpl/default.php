<?php
/**
 * @package     Yajem.Administrator
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user      	= JFactory::getUser();
$userId    	= $user->get('id');
$listOrder 	= $this->escape($this->state->get('list.ordering'));
$listDirn  	= $this->escape($this->state->get('list.direction'));

?>

<form action="<?php echo JRoute::_('index.php?option=com_yajem&view=attendees'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
	<?php endif; ?>

		<!-- Loading Filters and Sort Options -->
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>

		<div class="clearfix"></div>

		<table class="table table-striped" id="itemList">
			<thead>
			<tr>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value=""
						   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
				</th>
				<th class='left'>
					<?php echo JHtml::_('searchtools.sort', 'COM_YAJEM_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
					<?php echo JHtml::_('searchtools.sort', 'COM_YAJEM_TITLE_EVENT', 'event', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
					<?php echo JHtml::_('searchtools.sort', 'COM_YAJEM_ATTENDEES_ATTENDEE', 'attendee', $listDirn, $listOrder); ?>
				</th>
				<th class="left">
					<?php echo JHtml::_('searchtools.sort', 'COM_YAJEM_TITLE_ATTENDEES_STATUS', 'status', $listDirn, $listOrder); ?>
				</th>
			</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$link 		= 'index.php?option=com_yajem&amp;task=attendee.edit&amp;id=' . (int) $item->id;
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>

					<td>
						<?php echo $item->id; ?>
					</td>

					<td>
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_($link); ?>">
								<?php echo $this->escape($item->event); ?></a>
						<?php else: ?>
							<?php echo $this->escape($item->event); ?>
						<?php endif; ?>
					</td>

					<td>
						<?php echo $item->attendee; ?>
					</td>

					<td>
						<?php
						switch ($item->status)
						{
							case 0:
								echo JText::_("COM_YAJEM_NOT_DECIDED");
								break;
							case 1:
								echo JText::_("COM_YAJEM_ATTENDING");
								break;
							case 2:
								echo JText::_("COM_YAJEM_NOT_ATTENDING");
								break;
							case 3:
								echo JText::_("COM_YAJEM_ON_WAITINGLIST");
								break;
						}
						?>
					</td>

				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="boxchecked" value="0"/>
		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
