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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Session\Session;
use Joomla\Component\Content\Administrator\Helper\ContentHelper;
use Sda\Component\Sdajem\Administrator\Helper\AttendingHelper;
use Sda\Component\Sdajem\Site\Enums\AttendingStatusEnum;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;

/* @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('bootstrap.dropdown');
$wa->useScript('bootstrap.collapse');
$wa->useScript('table.columns');
$wa->useScript('form.validate');
$wa->getRegistry()->addExtensionRegistryFile('com_sdajem');
$wa->registerAndUseStyle('sdajem', 'com_sdajem/sdajem.css');

$canChange = true;
$canDo = ContentHelper::getActions('com_sdajem');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';
if ($saveOrder && !empty($this->items)) {
	$saveOrderingUrl = 'index.php?option=com_sdajem&task=events.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
}

$params = $params = ComponentHelper::getParams('com_sdajem');
$currentUser = Factory::getApplication()->getIdentity();

$userAuthorizedViewLevels = $currentUser->getAuthorisedViewLevels();

/* @var \Sda\Component\Sdajem\Administrator\Model\Items\EventsItemModel $item */
?>
<div class="sdajem_content_container">
    <form action="<?php echo Route::_('index.php?view=events'); ?>" method="post" name="adminForm" id="adminForm">
        <div>
            <?php if ($canDo->get('core.create')) : ?>
                <div class="mb-2">
                    <div class="btn-group sda_button_spacer" role="group" aria-label="basic group">
                        <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('event.add')">
                            <span class="fas fa-plus-circle" aria-hidden="true"></span>
                            <?php echo Text::_('COM_SDAJEM_EVENT_ADD'); ?>
                        </button>

                        <?php if ($params->get('sda_events_new_location')) :?>
                        <button type="button" class="btn btn-secondary" onclick="Joomla.submitbutton('location.add')">
                            <span class="fas fa-plus-circle" aria-hidden="true"></span>
                            <?php echo Text::_('COM_SDAJEM_LOCATION_ADD'); ?>
                        </button>
                        <?php endif; ?>
                    </div>

                    <?php if ($canDo->get('core.delete')) : ?>
                    <div class="btn-group sda_button_spacer d-sm-inline-block d-none" role="group" aria-label="Attending group">
                        <button type="button" class="btn btn-danger" onclick="Joomla.submitbutton('event.delete')">
                            <span class="fas fa-trash" aria-hidden="true"></span>
                            <?php echo Text::_('COM_SDAJEM_EVENT_DELETE'); ?>
                        </button>
                    </div>
                    <?php endif; ?>

                    <?php
                    if ($params->get('sda_use_attending'))
                    {
	                    echo '<div class="btn-group sda_button_spacer d-sm-inline-block d-none" role="group" aria-label="Attending group">';
	                    foreach (AttendingStatusEnum::cases() as $stat)
	                    {
		                    if ($stat != AttendingStatusEnum::NA)
		                    {
			                    $text = '<button type="button" class="btn ' . $stat->getButtonClass() . '" onclick="Joomla.submitbutton(\'' . $stat->getAction() . '\')">'
				                    . '<span class="icon-spacer ' . $stat->getIcon() . '" aria-hidden="true"></span>';
			                    $text .= Text::_($stat->getButtonLabel()) . '</button>';
			                    echo $text;
		                    }
	                    }
                        echo '</div>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="j-main-container" class="j-main-container">
                    <form action="<?php echo Route::_('index.php?view=events'); ?>" method="post" name="adminForm1" id="adminForm1">
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
                                <?php if (!$currentUser->guest) :?>
                                <td style="width:1%" class="text-center d-sm-table-cell d-none">
                                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                                </td>
                                <?php endif; ?>
                                <th scope="col" style="width:1%" class=" d-sm-table-cell d-none">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_SDAJEM_TABLE_TABLEHEAD_NAME', 'a.title', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" style="width:10%" class="d-none d-sm-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_SDAJEM_TABLE_TABLEHEAD_DATE', 'a.startDateTime', $listDirn, $listOrder); ?>
                                </th>
                                <?php if (!$currentUser->guest) : ?>
                                <th class="d-none d-sm-table-cell">
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
                            $attending = new \Sda\Component\Sdajem\Site\Model\AttendingModel();
                            foreach ($this->items as $i => $item) :
                                ?>
                            <?php // if(in_array($item->access, $userAuthorizedViewLevels)) : ?>
                                <tr class="row<?php echo $i % 2; ?>">
	                                <?php if (!$currentUser->guest) :?>
                                    <td class="text-center d-sm-table-cell d-none">
                                        <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                                    </td>
                                    <?php endif; ?>
                                    <th scope="row" class="has-context col-4">
                                        <div>
                                        <a href="<?php echo Route::_('index.php?option=com_sdajem&view=event&id=' . (int) $item->id); ?>">
                                            <?php echo $this->escape($item->title); ?>
                                        </a>
                                        </div>
                                        <?php if ($params->get('sda_use_location')) :?>
                                        <div class="small">
                                            <?php echo Text::_('COM_SDAJEM_LOCATION') . ': ' . $this->escape($item->location_name);?>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($params->get('sda_use_attending')) :?>
                                        <div class="small">
                                            <?php
                                            if (!$currentUser->guest)
                                            {
	                                            $attStatus = (AttendingHelper::getAttendingStatusToEvent($currentUser->id,
		                                            $item->id)) ? AttendingHelper::getAttendingStatusToEvent($currentUser->id,
		                                            $item->id)->status : 0;
	                                            echo AttendingStatusEnum::from($attStatus)->getStatusBadge();
                                            }
                                            ?>
                                        </div>
                                        <?php endif; ?>
                                    </th>
                                    <td class="d-md-table-cell">
                                        <?php
                                        if ($item->allDayEvent) {
                                            echo HTMLHelper::date($item->startDateTime,'d.m.Y',true);
	                                        echo ' - ';
	                                        echo HTMLHelper::date($item->endDateTime, 'd.m.Y', true);
                                        } else {
                                            echo HTMLHelper::date($item->startDateTime,'d.m.Y H:i',true);
	                                        echo ' - ';
	                                        echo HTMLHelper::date($item->endDateTime, 'd.m.Y H:i', true);
                                        }
                                        ?>
                                    </td>
                                    <?php if (!$currentUser->guest) : ?>
                                    <td class="d-sm-table-cell d-none">
                                        <?php if ($currentUser->id == $item->organizerId || $canDo->get('core.manage')) : ?>
                                        <div class="sda_form">
                                            <?php
                                            $class = EventStatusEnum::from($item->eventStatus)->getStatusColorClass();
                                            ?>
                                            <button type="button" class="btn btn-sm <?php echo $class; ?> dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
	                                            <?php echo Text::_(EventStatusEnum::from($item->eventStatus)->getStatusLabel()); ?>
                                            </button>
                                            <ul class="dropdown-menu">

                                            <?php foreach (EventStatusEnum::cases() as $status) : ?>
                                                <?php if ($status != EventStatusEnum::from($item->eventStatus)) : ?>
                                                    <li><?php echo HTMLHelper::_('sdajemIcon.switchEventStatus',$item, $status); ?></li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <?php else: ?>
                                        <div>
		                                    <?php echo EventStatusEnum::from($item->eventStatus)->getStatusBadge(); ?>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?php
                                            if ($item->eventStatus <> EventStatusEnum::PLANING->value) {
                                                echo $item->attendeeCount;
                                            } else {
	                                            echo $item->feedbackCount . ' ' . Text::_('COM_SDAJEM_FEEDBACK_COUNT') . '</br>';
                                                if ($canDo->get('core.manage') ||
	                                                ($canDo->get('core.edit.own') && $item->created_by == $currentUser->id)
                                                ) {
	                                                echo $item->attendeeCount . ' ' . Text::_('COM_SDAJEM_ATTENDEE_COUNT');
                                                }
                                            }
                                        ?>
                                    </td>
                                    <?php endif; ?>
                                    <td class="small d-none d-md-table-cell">
                                        <?php if ($canDo->get('core.edit') ||
                                                 ($canDo->get('core.edit.own') && $item->created_by == $currentUser->id)
                                        ) : ?>

                                            <div class="icons">
                                                <?php echo HTMLHelper::_('sdajemIcon.edit', $item, $params); ?>
                                            </div>

                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php //endif; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                        <?php echo $this->pagination->getListFooter(); ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>

        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
	    <?php echo HTMLHelper::_('form.token'); ?>

    </form>
</div>