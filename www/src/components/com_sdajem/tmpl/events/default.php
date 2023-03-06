<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_sdajem
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Session\Session;
use Joomla\Component\Content\Administrator\Helper\ContentHelper;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;

$wa = $this->document->getWebAssetManager();
$wa->useScript('table.columns');
$wa->useScript('form.validate');
$wa->registerAndUseStyle('sdajem', 'com_sdajem/sdajem.css');

$canChange = true;
$canDo = ContentHelper::getActions('com_sdajem');

$assoc = Associations::isEnabled();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';
if ($saveOrder && !empty($this->items)) {
	$saveOrderingUrl = 'index.php?option=com_sdajem&task=events.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
}

$params = $params = ComponentHelper::getParams('com_sdajem');

/* @var \Sda\Component\Sdajem\Administrator\Model\Items\EventsItemModel $item */
?>
<div class="sdajem_content_container">
    <form action="<?php echo Route::_('index.php?view=events'); ?>" method="post" name="adminForm" id="adminForm">
        <div>
            <?php if ($canDo->get('core.create')) : ?>
                <div class="mb-2">
                    <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('event.add')">
                        <span class="fas fa-plus-circle" aria-hidden="true"></span>
                        <?php echo Text::_('COM_SDAJEM_EVENT_ADD'); ?>
                    </button>

                    <?php if ($params->get('sda_events_new_location')) :?>
                    <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('location.add')">
                        <span class="fas fa-plus-circle" aria-hidden="true"></span>
                        <?php echo Text::_('COM_SDAJEM_LOCATION_ADD'); ?>
                    </button>
                    <?php endif; ?>

                    <?php if ($canDo->get('core.delete')) : ?>
                    <button type="button" class="btn btn-danger" onclick="Joomla.submitbutton('event.delete')">
                        <span class="fas fa-trash" aria-hidden="true"></span>
                        <?php echo Text::_('COM_SDAJEM_EVENT_DELETE'); ?>
                    </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
                <?php if (empty($this->items)) : ?>
                    <div class="alert alert-warning">
                        <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
                <?php else : ?>
                    <table class="table table-striped" id="eventList">
                        <caption class="visually-hidden">
                            <?php echo Text::_('COM_SDAJEM_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
                        </caption>
                        <thead>
                        <tr>
                            <td style="width:1%" class="text-center">
                                <?php echo HTMLHelper::_('grid.checkall'); ?>
                            </td>
                            <th scope="col" style="width:1%" class="d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('searchtools.sort', 'COM_SDAJEM_TABLE_TABLEHEAD_NAME', 'a.title', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:10%" class="d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('searchtools.sort', 'COM_SDAJEM_TABLE_TABLEHEAD_STARTDATE', 'a.startDateTime', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:10%" class="d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('searchtools.sort', 'COM_SDAJEM_TABLE_TABLEHEAD_ENDDATE', 'a.endDateTime', $listDirn, $listOrder); ?>
                            </th>
                            <?php if (!Factory::getApplication()->getIdentity()->guest) : ?>
                            <th class="d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('searchtools.sort', 'COM_SDAJEM_TABLEHEAD_EVENTS_STATUS', 'a.eventStatus', $listDirn, $listOrder); ?>
                            </th>
                            <th class="d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('searchtools.sort', 'COM_SDAJEM_TABLEHEAD_EVENTS_ATTENDEE_COUNT', 'attendeeCount', $listDirn, $listOrder); ?>
                            </th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = count($this->items);
                        foreach ($this->items as $i => $item) :
                            ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td class="text-center">
                                    <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                                </td>
                                <th scope="row" class="has-context col-4">
                                    <div>
                                    <a href="<?php echo Route::_('index.php?option=com_sdajem&view=event&id=' . (int) $item->id); ?>">
                                        <?php echo $this->escape($item->title); ?>
                                    </a>
                                    </div>
                                    <div class="small">
                                        <?php echo Text::_('COM_SDAJEM_LOCATION') . ': ' . $this->escape($item->location_name);?>
                                    </div>
                                    <div class="small">
                                        <?php echo Text::_('JCATEGORY') . ': ' . $this->escape($item->category_title); ?>
                                    </div>
                                </th>
                                <td class="d-md-table-cell">
                                    <?php
                                    if ($item->allDayEvent) {
                                        echo HTMLHelper::date($item->startDateTime,'d.m.Y',true);
                                    } else {
                                        echo HTMLHelper::date($item->startDateTime,'d.m.Y H:i',true);
                                    }
                                    ?>
                                </td>
                                <td class="d-none d-md-table-cell">
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
                                <?php if (!Factory::getApplication()->getIdentity()->guest) : ?>
                                <td class="d-md-table-cell">
                                    <div>
                                    <?php echo Text::_(EventStatusEnum::from($item->eventStatus)->getStatusLabel()); ?>
                                    </div>

                                    <?php if (Factory::getApplication()->getIdentity()->id == $item->organizerId) : ?>
                                    <div class="sda_form">
                                        <form action="<?php echo Route::_('index.php?view=event'); ?>" method="post" name="eventForm<?php echo $item->id; ?>" id="eventForm<?php echo $item->id; ?>">
                                            <?php foreach (EventStatusEnum::cases() as $status) : ?>
                                                <?php if ($status != EventStatusEnum::from($item->eventStatus) && $status != EventStatusEnum::OPEN) : ?>
                                                    <button type="button" class="sda_button_spacer btn btn-primary" onclick="Joomla.submitbutton('<?php echo $status->getEventAction();?>','eventForm<?php echo $item->id;?>')">
                                                    <span class="<?php echo $status->getIcon();?>" aria-hidden="true"></span>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <input type="hidden" name="eventId" value="<?php echo $item->id; ?>"/>
                                            <input type="hidden" name="task" value=""/>
                                            <input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
                                            <?php echo HTMLHelper::_('form.token'); ?>
                                        </form>
                                    </div>
                                    <?php endif; ?>

                                </td>
                                <td class="d-none d-md-table-cell">
                                    <?php echo $item->attendeeCount; ?>
                                </td>
                                <?php endif; ?>
                                <td class="small d-none d-md-table-cell">
                                    <?php if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) : ?>
                                    <form action="<?php echo Route::_('index.php?view=events'); ?>" method="post" name="editForm" id="editForm">
                                        <div class="icons">
                                            <?php echo HTMLHelper::_('eventicon.edit', $item, $params); ?>
                                        </div>
                                        <input type="hidden" name="task" value=""/>
                                        <input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
	                                    <?php echo HTMLHelper::_('form.token'); ?>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>