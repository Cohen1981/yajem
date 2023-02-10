<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Contact\Administrator\Model\ContactModel;
use Sda\Component\Sdajem\Site\Enums\AttendingStatusEnum;
use Sda\Component\Sdajem\Site\Helper\EventHtmlHelper;
use Sda\Component\Sdajem\Site\Model\AttendeeModel;
use Sda\Component\Sdajem\Site\Model\EventModel;
use Sda\Component\Sdajem\Site\Model\UserModel;

$wa=$this->document->getWebAssetManager();
$wa->registerAndUseStyle('sdajem', 'com_sdajem/sdajem.css');

$canDo   = ContentHelper::getActions('com_sdajem', 'category', $this->item->catid);
$user = Factory::getApplication()->getIdentity();
try
{
	$canEdit = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $user->id);
}
catch (Exception $e)
{
}
$tparams = $this->item->params;

/* @var EventModel $event */
$event = $this->item;

/* @var UserModel $organizer */
if (isset($event->organizer))
	$organizer = $event->organizer;

/* @var ContactModel $host */
if (isset($event->host))
	$host = $event->host;

/* @var \Sda\Component\Sdajem\Site\Model\LocationModel $location */
if (isset($event->location))
    $location = $event->location;
?>
<div class="sdajem_content_container">
    <div class="sda_row">
        <div class="sda_event_head">
            <div>
                <h3>
                    <?php echo $this->escape($event->title);?>
                </h3>
                <h4>
                    <?php
                    if ($event->allDayEvent) {
                        echo HTMLHelper::date($event->startDateTime,'d.m.Y',true);
                        echo ' - ';
                        echo HTMLHelper::date($event->endDateTime,'d.m.Y',true);
                    } else {
                        echo HTMLHelper::date($event->startDateTime,'d.m.Y H:i',true);
                        echo ' - ';
                        echo HTMLHelper::date($event->endDateTime,'d.m.Y H:i',true);
                    }
                    ?>
                </h4>
            </div>
            <?php if (!empty($canEdit))
            {
                if ($canEdit) : ?>
                    <div class="icons">
                        <?php echo HTMLHelper::_('eventicon.edit', $event, $tparams); ?>
                    </div>
                <?php endif;
            } ?>
        </div>
    </div>

    <div class="sda_row clearfix">
        <?php if ($event->image) : ?>
            <div class="sdajem_teaser_image">
                <?php echo HTMLHelper::image($event->image,'',['class'=>'float-start pe-2']); ?>
            </div>
        <?php endif; ?>
        <p><?php echo $event->description; ?></p>
    </div>

    <div class="accordion" id="accordionEvent">
	    <?php if (isset($event->location)) : ?>
        <div class="accordion-item">
            <h5 class="accordion-header" id="headingLocation">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLocation" aria-expanded="true" aria-controls="collapseLocation">
                    <h5><?php echo Text::_('COM_SDAJEM_LOCATION'); ?>: <?php echo $location->title; ?></h5>
                </button>
            </h5>
            <div id="collapseLocation" class="accordion-collapse collapse" aria-labelledby="headingLocation" data-bs-parent="#accordionEvent">
                <div class="accordion-body clearfix">
	                <?php if (!empty($canEdit))
	                {
		                if ($canEdit) : ?>
                            <div class="icons">
				                <?php echo HTMLHelper::_('eventicon.editLocation', $location, $tparams); ?>
                            </div>
		                <?php endif;
	                } ?>
                    <?php if ($location->image): ?>
                        <div class="sdajem_teaser_image">
                            <?php echo HTMLHelper::image($location->image,'',['class'=>'float-start pe-2']); ?>
                        </div>
                    <?php endif; ?>
                    <p><?php echo $location->street; ?></p>
                    <p><?php echo $location->postalCode; ?></p>
                    <p><?php echo $location->city; ?></p>
                </div>
            </div>
        </div>
	    <?php endif; ?>
	    <?php if($tparams->get('sda_use_organizer') && isset($organizer)) : ?>
            <div class="accordion-item">
                <h5 class="accordion-header" id="headingOrganizer">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrganizer" aria-expanded="true" aria-controls="collapseOrganizer">
                        <h5><?php echo Text::_('COM_SDAJEM_FIELD_ORGANIZER_LABEL'); ?>: <?php echo $organizer->user->name; ?></h5>
                    </button>
                </h5>
                <div id="collapseOrganizer" class="accordion-collapse collapse" aria-labelledby="headingOrganizer" data-bs-parent="#accordionEvent">
                    <div class="accordion-body">
                        <p><?php echo $organizer->user->username; ?></p>
                    </div>
                </div>
            </div>
	    <?php endif; ?>
	    <?php if(isset($event->hostId)) : ?>
            <div class="accordion-item">
                <h5 class="accordion-header" id="headingHost">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHost" aria-expanded="true" aria-controls="collapseHost">
                        <h5><?php echo Text::_('COM_SDAJEM_FIELD_HOST_LABEL'); ?>: <?php echo $host->get('name'); ?></h5>
                    </button>
                </h5>
                <div id="collapseHost" class="accordion-collapse collapse" aria-labelledby="headingHost" data-bs-parent="#accordionEvent">
                    <div class="accordion-body">
	                    <?php if (!empty($canEdit))
	                    {
		                    if ($canEdit) : ?>
                                <div class="icons">
				                    <?php echo HTMLHelper::_('contacticon.edit', $host, $tparams); ?>
                                </div>
		                    <?php endif;
	                    } ?>
                        <p><?php echo $host->get('telephone'); ?></p>
                        <p><?php echo $host->get('email_to'); ?></p>
                        <p><?php echo $host->get('mobile'); ?></p>
                    </div>
                </div>
            </div>
	    <?php endif; ?>
    </div>

    <?php if ($tparams->get('sda_use_attending')) : ?>
        <div id="attendings" class="sda_row">
            <h5><?php echo Text::_('COM_SDAJEM_ATTENDESS'); ?></h5>
            <div class="sda_row">
            <?php if (isset($event->attendings)) : ?>
            <div class="sda_attendee_container">
                <?php if (!$user->guest) : ?>
                    <?php foreach ($event->attendings as $i => $attending) : ?>
                        <?php EventHtmlHelper::renderAttendee(new AttendeeModel($attending), $tparams->get('sda_avatar_field_name')); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php echo count($event->attendings); ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            </div>
            <div class="sda_row">
            <?php if (!$user->guest) : ?>
                <?= HTMLHelper::_('form.token'); ?>
                <?php echo HTMLHelper::_('eventicon.register', $event, $tparams); ?>
            <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
