<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_sdajem
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Session\Session;
use Joomla\Component\Content\Administrator\Helper\ContentHelper;

$wa = $this->document->getWebAssetManager();
$wa->useScript('table.columns');
$canChange = true;
$canDo = ContentHelper::getActions('com_sdajem');

$assoc = Associations::isEnabled();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';
if ($saveOrder && !empty($this->items)) {
	$saveOrderingUrl = 'index.php?option=com_sdajem&task=events.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
}

$params = $this->get('State')->get('params');

/* @var \Sda\Component\Sdajem\Administrator\Model\EventsItemModel $item */
?>
<form action="<?php echo Route::_('index.php?view=events'); ?>" method="post" name="adminForm" id="adminForm">
    <div>
		<?php if ($canDo->get('core.create')) : ?>
            <div class="mb-2">
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('event.add')">
                    <span class="fas fa-check" aria-hidden="true"></span>
					<?php echo Text::_('COM_SDAJEM_EVENT_ADD'); ?>
                </button>
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('location.add')">
                    <span class="fas fa-check" aria-hidden="true"></span>
					<?php echo Text::_('COM_SDAJEM_LOCATION_ADD'); ?>
                </button>
            </div>
		<?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
	            <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
				<?php if (empty($this->items)) : ?>
                    <div class="alert alert-warning">
						<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
				<?php else : ?>
                    <table class="table" id="eventList">
                        <caption class="visually-hidden">
							<?php echo Text::_('COM_SDAJEM_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
                        </caption>
                        <thead>
                        <tr>
                            <th scope="col" style="width:1%" class="text-center d-none d-md-table-cell">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_SDAJEM_TABLE_TABLEHEAD_NAME', 'a.title', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:10%" class="d-none d-md-table-cell">
		                        <?php echo HTMLHelper::_('searchtools.sort', 'COM_SDAJEM_TABLE_TABLEHEAD_STARTDATE', 'a.startDateTime', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:10%" class="d-none d-md-table-cell">
		                        <?php echo HTMLHelper::_('searchtools.sort', 'COM_SDAJEM_TABLE_TABLEHEAD_ENDTDATE', 'a.endDateTime', $listDirn, $listOrder); ?>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
						$n = count($this->items);
						foreach ($this->items as $i => $item) :
							?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <th scope="row" class="has-context">
									<?php $editIcon = '<span class="fa fa-pencil-square mr-2" aria-hidden="true"></span>'; ?>
                                    <a href="<?php echo Route::_('index.php?option=com_sdajem&view=event&id=' . (int) $item->id); ?>">
										<?php echo $this->escape($item->title); ?>
                                    </a>
                                    <div class="small">
                                        <?php echo Text::_('COM_SDAJEM_LOCATION') . ': ' . $this->escape($item->location_name);?>
                                    </div>
                                    <div class="small">
										<?php echo Text::_('JCATEGORY') . ': ' . $this->escape($item->category_title); ?>
                                    </div>
                                </th>
                                <td class="small d-md-table-cell">
		                            <?php
		                            if ($item->allDayEvent) {
			                            echo HTMLHelper::date($item->startDateTime,'d.m.Y',true);
		                            } else {
			                            echo HTMLHelper::date($item->startDateTime,'d.m.Y H:i',true);
		                            }
                                    ?>
                                </td>
                                <td class="small d-none d-md-table-cell">
		                            <?php
		                            if ($item->allDayEvent)
		                            {
			                            echo HTMLHelper::date($item->endDateTime, 'd.m.Y', true);
		                            }
		                            else
		                            {
			                            echo HTMLHelper::date($item->endDateTime, 'd.m.Y H:i', true);
		                            }

		                            ?>
                                </td>
                                <td class="small d-none d-md-table-cell">
	                                <?php if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) : ?>
                                        <div class="icons">
			                                <?php echo HTMLHelper::_('eventicon.edit', $item, $params); ?>
                                        </div>
	                                <?php endif; ?>
                                </td>
                            </tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>

				<?php endif; ?>
                <input type="hidden" name="task" value="">
                <input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
				<?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>