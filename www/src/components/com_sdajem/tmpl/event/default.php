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
use Joomla\Component\Contact\Administrator\Model\ContactModel;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Model\EventModel;
use Sda\Component\Sdajem\Site\Model\UserModel;

$wa=$this->document->getWebAssetManager();

$wa->getRegistry()->addExtensionRegistryFile('com_sdajem');
$wa->useStyle('com_sdajem.sdajem');
$wa->useScript('com_sdajem.checkbox');

$wa->useScript('bootstrap.dropdown');
$wa->useScript('bootstrap.collapse');

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
{
	$location = $event->location;

	if ($location->latlng)
	{
		$lAddressString = urlencode($location->latlng);
	}
	else
	{
		$lAddressString = urlencode($location->street) . "+" . urlencode($location->postalCode) . "+" . urlencode($location->city);
	}
}

$uAdressString = null;

if (!$user->guest)
{
	/** @var UserModel $userModel */
    $userModel = new UserModel($user->id);

	$uAdressString = urlencode($userModel->profile['address1']) . "+" .
        	urlencode($userModel->profile['postal_code']) . "+" .
			urlencode($userModel->profile['city']);
}

$currentUser = Factory::getApplication()->getIdentity();

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
                <?php if (!$currentUser->guest) : ?>
                <h5>
	                <?php if ($currentUser->id == $event->organizerId || $canDo->get('core.manage')) : ?>

		                <?php
		                $class = EventStatusEnum::from($event->eventStatus)->getStatusColorClass();
		                ?>
                        <button type="button" class="btn btn-sm <?php echo $class; ?> dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
			                <?php echo Text::_(EventStatusEnum::from($event->eventStatus)->getStatusLabel()); ?>
                        </button>
                        <ul class="dropdown-menu">

			                <?php foreach (EventStatusEnum::cases() as $status) : ?>
				                <?php if ($status != EventStatusEnum::from($event->eventStatus)) : ?>
                                    <li><?php echo HTMLHelper::_('sdajemIcon.switchEventStatus',$event, $status); ?></li>
				                <?php endif; ?>
			                <?php endforeach; ?>
                        </ul>

	                <?php else: ?>

		                <?php echo EventStatusEnum::from($event->eventStatus)->getStatusBadge(); ?>

	                <?php endif; ?>
                </h5>
                <?php endif; ?>
            </div>
            <?php if (!empty($canEdit))
            {
                if ($canEdit) : ?>
                    <div class="icons">
                        <?php echo HTMLHelper::_('sdajemIcon.edit', $event, $tparams); ?>
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
        <?php if ($event->url) : ?>
            <a href="<?php echo $event->url; ?>" target="_blank"><?php echo Text::_('COM_SDAJEM_EVENT_URL'); ?></a>
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

            <div id="collapseLocation" class="accordion-collapse collapse show" aria-labelledby="headingLocation" data-bs-parent="#accordionEvent">
                <div class="accordion-body clearfix">

                    <?php if ($user->authorise('core.edit', 'com_sdajem') || ($user->authorise('core.edit.own', 'com_sdajem') && $location->created_by == $user->id)) : ?>
                            <div class="icons float-end">
				                <?php echo HTMLHelper::_('sdajemIcon.editLocation', $location, $tparams); ?>
                            </div>
		            <?php endif; ?>

                    <?php if(isset($lAddressString)): ?>
                    <p>
                        <a href="https://www.google.de/maps?q=<?php echo $lAddressString; ?>"
                           class="me-2" target="_blank">
                            <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
			                <?php echo Text::_('COM_SDAJEM_LOCATION_SHOW_ON_MAP'); ?>
                        </a>

		                <?php if ($uAdressString): ?>
                            <a href="https://www.google.de/maps?saddr=<?php echo $uAdressString; ?>&daddr=<?php echo $lAddressString; ?>"
                               class="me-2" target="_blank">
                                <i class="fas fa-route" aria-hidden="true"></i>
				                <?php echo Text::_('COM_SDAJEM_LOCATION_SHOW_ROUTE'); ?>
                            </a>
		                <?php endif; ?>
                    </p>
                    <?php endif; ?>

                    <?php if ($location->image): ?>
                        <div class="sdajem_teaser_image">
                            <?php echo HTMLHelper::image($location->image,'',['class'=>'float-start pe-2']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="loc_address">
                        <p><?php echo $location->street; ?></p>
                        <p><?php echo $location->postalCode; ?></p>
                        <p><?php echo $location->city; ?></p>
                    </div>
                </div>
            </div>
        </div>
	    <?php endif; ?>
	    <?php if($tparams->get('sda_use_organizer') && isset($organizer) && !$user->guest) : ?>
            <div class="accordion-item">
                <h5 class="accordion-header" id="headingOrganizer">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrganizer" aria-expanded="true" aria-controls="collapseOrganizer">
                        <h5><?php echo Text::_('COM_SDAJEM_FIELD_ORGANIZER_LABEL'); ?>: <?php echo $organizer->user->name; ?></h5>
                    </button>
                </h5>
                <div id="collapseOrganizer" class="accordion-collapse collapse" aria-labelledby="headingOrganizer" data-bs-parent="#accordionEvent">
                    <div class="accordion-body">
                        <p><?php echo $organizer->user->email; ?></p>
                    </div>
                </div>
            </div>
	    <?php endif; ?>
	    <?php if(isset($event->hostId)) : ?>
            <div class="accordion-item">
                <h5 class="accordion-header" id="headingHost">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHost" aria-expanded="true" aria-controls="collapseHost">
                        <h5><?php echo Text::_('COM_SDAJEM_FIELD_HOST_LABEL'); ?>: <?php echo $host->get('name'); ?></h5>
                    </button>
                </h5>
                <div id="collapseHost" class="accordion-collapse collapse" aria-labelledby="headingHost" data-bs-parent="#accordionEvent">
                    <div class="accordion-body">
	                    <?php if (!empty($canEdit))
	                    {
		                    if ($canEdit) : ?>
                                <div class="icons float-end">
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

        <?php if ($tparams->get('sda_use_attending') && !$user->guest): ?>
        <div class="accordion-item">
            <h5 class="accordion-header" id="headingAttendings">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAttendings" aria-expanded="true" aria-controls="collapseAttendings">
                    <h5><?php echo Text::_('COM_SDAJEM_FIELD_ATTENDINGS_LABEL'); ?></h5></br>
                </button>
            </h5>
            <div id="collapseAttendings" class="accordion-collapse collapse" aria-labelledby="headingAttendings" data-bs-parent="#accordionEvent">
                <div class="accordion-body">
                    <?php
                    if ($event->eventStatus == EventStatusEnum::PLANING->value) {
                        echo $this->loadTemplate('interest');
                    }
                    else
                    {
                        echo $this->loadTemplate('attendees');
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

	    <?php if ($tparams->get('sda_events_use_fittings') && !$user->guest): ?>
        <div class="accordion-item">
            <h5 class="accordion-header" id="headingPlaningArea">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePlaningArea" aria-expanded="true" aria-controls="collapsePlaningArea">
                    <h5><?php echo Text::_('COM_SDAJEM_PLANING_AREA_LABEL'); ?></h5></br>
                </button>
            </h5>
            <div id="collapsePlaningArea" class="accordion-collapse collapse" aria-labelledby="headingPlaningArea" data-bs-parent="#accordionEvent">
                <div class="accordion-body">

                    <?php echo $this->loadTemplate('planing'); ?>

                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
