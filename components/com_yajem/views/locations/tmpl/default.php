<?php
/**
 * @package     Yajem.Administrator
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

HtmlHelper::addIncludePath(JPATH_COMPONENT . '/helpers/');
HtmlHelper::_('bootstrap.tooltip');
HtmlHelper::_('behavior.multiselect');
HtmlHelper::_('formbehavior.chosen', 'select');

$user      	= JFactory::getUser();
$userId    	= $user->get('id');
$listOrder 	= $this->escape($this->state->get('list.ordering'));
$listDirn  	= $this->escape($this->state->get('list.direction'));
$saveOrder 	= $listOrder == 'a.ordering';
$useContact	= (bool) JComponentHelper::getParams('com_yajem')->get('use_location_contact');

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_yajem&task=locations.saveOrderAjax&tmpl=component';
	HtmlHelper::_('sortablelist.sortable', 'itemList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_yajem&view=locations'); ?>" method="post"
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
		<?php if (!(bool) $user->guest) : ?>
	        <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
            <div class="btn-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('editlocation.add')">
                        <i class="icon-new"></i> <?php echo JText::_('JNEW') ?>
                    </button>
                    <?php if(!$this->state->get('filter.published') == -2): ?>
                    <button type="button" class="btn" onclick="Joomla.submitbutton('editlocations.trash')">
                        <i class="icon-trash"></i> <?php echo JText::_('JTRASH') ?>
                    </button>
                    <?php endif; ?>
                    <?php if($this->state->get('filter.published') == -2): ?>
                    <button type="button" class="btn" onclick="Joomla.submitbutton('editlocations.delete')">
                        <i class="icon-delete"></i> <?php echo JText::_('JACTION_DELETE') ?>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
		<?php endif; ?>

		<div class="clearfix"></div>

		<table class="table table-striped" id="itemList">
			<thead>
			<tr>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo HtmlHelper::_('searchtools.sort', '', 'a.ordering',
						$listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
				</th>
				<?php if (!(bool) $user->guest) : ?>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value=""
						   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
				</th>
                    <?php if (isset($this->items[0]->published)): ?>
                        <th width="1%" class="nowrap center">
                            <?php echo HtmlHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
                        </th>
                    <?php endif; ?>
                <?php endif; ?>

				<th class='left'>
					<?php echo HtmlHelper::_('searchtools.sort', 'COM_YAJEM_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class="left">
					<?php echo JText::_('COM_YAJEM_LOCATION_IMAGE'); ?>
				</th>
				<th class='left'>
					<?php echo HtmlHelper::_('searchtools.sort', 'COM_YAJEM_TITLE_LOCATION', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
					<?php echo HtmlHelper::_('searchtools.sort', 'COM_YAJEM_LOCATIONS_CATID', 'a.catid', $listDirn, $listOrder); ?>
				</th>
				<?php if ($useContact): ?>
					<th class="left">
						<?php echo HtmlHelper::_('searchtools.sort', 'COM_YAJEM_TITLE_LOCATIONS_CONTACT', 'contact', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>
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

				$link 		= 'index.php?option=com_yajem&amp;task=editlocation.edit&amp;id=' . (int) $item->id;
				$published 	= HtmlHelper::_('jgrid.published', $item->published, $i, 'locations.', $canChange, 'cb');
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
					<?php if (!(bool) $user->guest) : ?>
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
									HtmlHelper::_('actionsdropdown.' . ((int) $item->published === 2 ? 'un' : '') . 'archive', 'cb' . $i, 'editlocations');
									HtmlHelper::_('actionsdropdown.' . ((int) $item->published === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'editlocations');

									// Render dropdown list
									echo HtmlHelper::_('actionsdropdown.render', $this->escape($item->title));
								}
								?>
							</div>
						</td>
					<?php endif; ?>
					<?php endif; ?>

					<td>
						<?php echo $item->id; ?>
					</td>

					<td>
						<?php
						$caption = $item->img->get('caption') ? : '';
						$src = JURI::root() . ($item->img->get('image') ? : '' );
						$html = '<p class="hasTooltip" style="display: yajem_inline-block" 
									data-html="true" data-toggle="tooltip" 
									data-placement="right" 
									title="<img width=\'150px\' height=\'150px\' src=\'%s\'>">%s</p>';
						$thumb = '<img style="display: yajem_inline-block" 
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
						<?php echo $item->catid; ?>
					</td>

					<?php if ($useContact): ?>
						<td>
							<?php echo $item->contact; ?>
						</td>
					<?php endif; ?>

				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="boxchecked" value="0"/>
		<input type="hidden" name="task" value=""/>
		<?php echo HtmlHelper::_('form.token'); ?>
	</div>
</form>
