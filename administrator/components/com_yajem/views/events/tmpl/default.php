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
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

HtmlHelper::addIncludePath(JPATH_COMPONENT . '/helpers/');
HtmlHelper::_('stylesheet', JUri::root() . 'media/com_yajem/css/style.css');
HtmlHelper::_('bootstrap.tooltip');
HtmlHelper::_('behavior.multiselect');
HtmlHelper::_('formbehavior.chosen', 'select');

$user      		= JFactory::getUser();
$userId    		= $user->get('id');
$listOrder 		= $this->escape($this->state->get('list.ordering'));
$listDirn  		= $this->escape($this->state->get('list.direction'));
$saveOrder 		= $listOrder == 'a.ordering';
$useHost		= (bool) JComponentHelper::getParams('com_yajem')->get('use_host');
$useOrganizer	= (bool) JComponentHelper::getParams('com_yajem')->get('use_organizer');
$currentDate 	= Factory::getDate('now', 'UTC');

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_yajem&task=events.saveOrderAjax&tmpl=component';
	HtmlHelper::_('sortablelist.sortable', 'itemList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_yajem&view=events'); ?>" method="post"
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
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo HtmlHelper::_('searchtools.sort', '', 'a.ordering',
						$listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
				</th>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value=""
						   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
				</th>
				<?php if (isset($this->items[0]->published)): ?>
					<th width="1%" class="nowrap center">
						<?php echo HtmlHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>

				<th class='left'>
					<?php echo HtmlHelper::_('searchtools.sort', 'COM_YAJEM_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class="left">
					<?php echo JText::_('COM_YAJEM_EVENT_IMAGE'); ?>
				</th>
				<th class='left'>
					<?php echo HtmlHelper::_('searchtools.sort', 'COM_YAJEM_TITLE_EVENT', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo HtmlHelper::_('searchtools.sort', 'COM_YAJEM_EVENTS_STARTDATE', 'a.startDate', $listDirn, $listOrder); ?> <br/>
					<?php echo HtmlHelper::_('searchtools.sort', 'COM_YAJEM_EVENTS_ENDDATE', 'a.endDate', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
					<?php echo HtmlHelper::_('searchtools.sort', 'COM_YAJEM_EVENTS_CATID', 'a.catid', $listDirn, $listOrder); ?>
				</th>
				<?php if ($useHost): ?>
					<th class="left">
						<?php echo HtmlHelper::_('searchtools.sort', 'COM_YAJEM_TITLE_EVENTS_HOST', 'hoster', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>
				<th>
					<?php echo HtmlHelper::_('searchtools.sort', 'COM_YAJEM_TITLE_EVENTS_ORGANIZER', 'organizer', $listDirn, $listOrder); ?>
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
				$canCreate = $user->authorise('core.create', 'com_yajem');
				$canEdit = $user->authorise('core.edit', 'com_yajem');
				$canChange = $user->authorise('core.edit.state', 'com_yajem');
				$item->img = new Registry;
				$item->img->loadString($item->image);

				$link 		= 'index.php?option=com_yajem&amp;task=event.edit&amp;id=' . (int) $item->id;
				$published 	= HtmlHelper::_('jgrid.published', $item->published, $i, 'events.', $canChange, 'cb');
				$start		= (bool) $item->allDayEvent ? Factory::getDate($item->startDate, 'UTC') : Factory::getDate($item->startDateTime, 'UTC');
				?>

				<tr class="row<?php echo $i % 2; ?>">
					<td class="order nowrap center hidden-phone">
						<?php
						$iconClass = '';
						if (!$canChange)
						{
							$iconClass = ' inactive';
						}
						elseif (!$saveOrder)
						{
							$iconClass = ' inactive tip-top hasTooltip" title="' . HtmlHelper::_('tooltipText', 'JORDERINGDISABLED');
						}
						?>
						<span class="sortable-handler<?php echo $iconClass ?>">
							<span class="icon-menu" aria-hidden="true"></span>
						</span>
						<?php if ($canChange && $saveOrder) : ?>
							<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
					</td>
					<?php endif; ?>
					<td class="hidden-phone">
						<?php echo HtmlHelper::_('grid.id', $i, $item->id); ?>
					</td>
					<?php if (isset($this->items[0]->published)): ?>
						<td class="center">
							<div class="btn-group">
								<?php echo $published ?>
								<?php
								if ($canChange)
								{
									// Create dropdown items
									HtmlHelper::_('actionsdropdown.' . ((int) $item->published === 2 ? 'un' : '') . 'archive', 'cb' . $i, 'events');
									HtmlHelper::_('actionsdropdown.' . ((int) $item->published === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'events');

									// Render dropdown list
									echo HtmlHelper::_('actionsdropdown.render', $this->escape($item->title));
								}
								?>
							</div>
						</td>
					<?php endif; ?>

					<td>
						<?php echo $item->id; ?>
					</td>

					<td>
						<?php
						$caption = $item->img->get('caption') ? : '';
						$src = JURI::root() . ($item->img->get('image') ? : '' );
						$html = '<p class="hasTooltip" style="display: inline-block" 
									data-html="true" data-toggle="tooltip" 
									data-placement="right" 
									title="<img width=\'150px\' height=\'150px\' src=\'%s\'>">%s</p>';
						$thumb = '<img style="display: inline-block" 
									width="50px" height="50px" src="' . $src . '"
									title="' . $caption . '" />';
						echo sprintf($html, $src, $thumb); ?>
					</td>

					<td>
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_($link); ?>">
								<?php echo $this->escape($item->title); ?></a>
						<?php else: ?>
							<?php echo $this->escape($item->title); ?>
						<?php endif; ?>
					</td>

					<td>
						<?php
						if ((bool) $item->allDayEvent)
						{
							echo date('d.m.Y', strtotime($item->startDate)) . '&nbsp;';
							if ($currentDate > $start)
							{
								echo '<i class="fas fa-exclamation-triangle" aria-hidden="true" style="color: #de2744"></i><br/>';
							}
							echo date('d.m.Y', strtotime($item->endDate));
						}
						else
						{
							echo date('d.m.Y H:i', strtotime($item->startDateTime)) . '&nbsp;';
							if ($currentDate > $start)
							{
								echo '<i class="fas fa-exclamation-triangle" aria-hidden="true" style="color: #de2744"></i><br/>';
							}
							echo date('d.m.Y H:i', strtotime($item->endDateTime));
						}
						?>
					</td>

					<td>
						<?php echo $item->catid; ?>
					</td>

					<?php if ($useHost): ?>
						<td>
							<?php echo $item->hoster; ?>
						</td>
					<?php endif; ?>
					<td>
						<?php echo $item->organizer['name']; ?>
					</td>

				</tr>

			<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="boxchecked" value="0"/>
		<input type="hidden" name="task" value=""/>
		<?php echo HtmlHelper::_('form.token'); ?>
	</div>
</form>
