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

$app = JFactory::getApplication();

if ($app->isClient('site'))
{
	JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));
}

JLoader::register('YajemHelperAdmin', JPATH_COMPONENT . '/helpers/yajem.php');
HtmlHelper::addIncludePath(JPATH_COMPONENT . '/helpers/');

HtmlHelper::_('behavior.core');
HtmlHelper::_('bootstrap.tooltip', '.hasTooltip', array('placement' => 'bottom'));
HtmlHelper::_('bootstrap.popover', '.hasPopover', array('placement' => 'bottom'));
HtmlHelper::_('formbehavior.chosen', 'select');
HtmlHelper::_('behavior.polyfill', array('event'), 'lt IE 9');
HtmlHelper::_('script', 'com_yajem/admin-locations-modal.js', array('version' => 'auto', 'relative' => true));

// Special case for the search field tooltip.
$searchFilterDesc = $this->filterForm->getFieldAttribute('search', 'description', null, 'filter');
HtmlHelper::_('bootstrap.tooltip', '#filter_search', array('title' => JText::_($searchFilterDesc), 'placement' => 'bottom'));

$function  = $app->input->getCmd('function', 'jSelectLocation');
$editor    = $app->input->getCmd('editor', '');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$onclick   = $this->escape($function);

if (!empty($editor))
{
	// This view is used also in com_menus. Load the xtd script only if the editor is set!
	JFactory::getDocument()->addScriptOptions('xtd-locations', array('editor' => $editor));
	$onclick = "jSelectLocation";
}
?>
<div class="container-popup">

	<form action="<?php echo JRoute::_('index.php?option=com_yajem&view=locations&layout=modal&tmpl=component&function=' . $function . '&' . JSession::getFormToken() . '=1'); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">

		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped table-condensed">
				<thead>
				<tr>
					<th width="1%" class="center nowrap">
						<?php echo HtmlHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
					<th class="nowrap title">
						<?php echo HtmlHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap">
						<?php echo HtmlHelper::_('searchtools.sort', 'JCATEGORY', 'a.catid', $listDirn, $listOrder); ?>
					</th>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="6">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
				</tfoot>
				<tbody>
				<?php
				$iconStates = array(
					-2 => 'icon-trash',
					0  => 'icon-unpublish',
					1  => 'icon-publish',
					2  => 'icon-archive',
				);
				?>
				<?php foreach ($this->items as $i => $item) : ?>
					<?php if ($item->language && JLanguageMultilang::isEnabled())
					{
						$tag = strlen($item->language);
						if ($tag == 5)
						{
							$lang = substr($item->language, 0, 2);
						}
						elseif ($tag == 6)
						{
							$lang = substr($item->language, 0, 3);
						}
						else {
							$lang = '';
						}
					}
					elseif (!JLanguageMultilang::isEnabled())
					{
						$lang = '';
					}
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<span class="<?php echo $iconStates[$this->escape($item->published)]; ?>" aria-hidden="true"></span>
						</td>
						<td>
							<a class="select-link" href="javascript:void(0)" data-function="<?php echo $this->escape($onclick); ?>" data-id="<?php echo $item->id; ?>" data-title="<?php echo $this->escape(addslashes($item->title)); ?>" data-uri="<?php echo $this->escape(YajemHelperAdmin::getLocationRoute($item->id, $item->catid)); ?>" data-language="<?php echo $this->escape($lang); ?>">
								<?php echo $this->escape($item->title); ?>
							</a>
							<div class="small">
								<?php echo JText::_('JCATEGORY') . ': ' . $this->escape($item->catid); ?>
							</div>
						</td>
						<td>
							<?php if (!empty($item->catid)) : ?>
								<?php echo $item->catid; ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="forcedLanguage" value="<?php echo $app->input->get('forcedLanguage', '', 'CMD'); ?>" />
		<?php echo HtmlHelper::_('form.token'); ?>

	</form>
</div>
