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
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Model\UserModel;
use Sda\Component\Sdajem\Site\View\Event\HtmlView;

/** @var HtmlView $this */

$wa=$this->getDocument()->getWebAssetManager();

$wa->getRegistry()->addExtensionRegistryFile('com_sdajem');
$wa->useStyle('com_sdajem.sdajem');
$wa->useScript('com_sdajem.checkbox');

$wa->useScript('bootstrap.dropdown');
$wa->useScript('bootstrap.collapse');
$wa->useScript('joomla.dialog-autocreate');

$canDo   = ContentHelper::getActions('com_sdajem', 'category', $this->item->catid);
$user = Factory::getApplication()->getIdentity();
$canEdit = false;
try
{
	$canEdit = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $user->id);
}
catch (Exception $e)
{
}
$tparams = $this->item->paramsRegistry;

$event = $this->item;

if (isset($this->organizer))
	$organizer = $this->organizer;

$host = null;
if (isset($this->host))
	$host = $this->host;

$location = null;
if (isset($this->location))
{
	$location = $this->location;

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
    $userModel = new UserModel($user->id);

    if ($userModel->profile)
    {
        $uAdressString = urlencode($userModel->profile['address1']) . "+" .
                urlencode($userModel->profile['postal_code']) . "+" .
                urlencode($userModel->profile['city']);
    }
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
                        echo HTMLHelper::date($event->startDateTime,'d.m.Y');
                        echo ' - ';
                        echo HTMLHelper::date($event->endDateTime,'d.m.Y');
                    } else {
                        echo HTMLHelper::date($event->startDateTime,'d.m.Y H:i');
                        echo ' - ';
                        echo HTMLHelper::date($event->endDateTime,'d.m.Y H:i');
                    }
                    ?>
                </h4>
                <?php if (!$currentUser->guest) : ?>
                <h5>
	                <?php if ($currentUser->id == $event->organizerId || $canDo->get('core.manage')) : ?>

		                <?php
		                $class = $event->eventStatus->getStatusColorClass();
		                ?>
                        <button type="button" class="btn btn-sm <?php echo $class; ?> dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
			                <?php echo Text::_($event->eventStatus->getStatusLabel()); ?>
                        </button>
                        <ul class="dropdown-menu">

			                <?php foreach (EventStatusEnum::cases() as $status) : ?>
				                <?php if ($status != $event->eventStatus) : ?>
                                    <li><?php echo HTMLHelper::_('sdajemIcon.switchEventStatus',$event, $status); ?></li>
				                <?php endif; ?>
			                <?php endforeach; ?>
                        </ul>

	                <?php else: ?>

		                <?php echo $event->eventStatus->getStatusBadge(); ?>

	                <?php endif; ?>
                </h5>
                <?php endif; ?>
	            <?php if($tparams->get('sda_use_organizer') && isset($organizer) && !$user->guest) : ?>
                    <h6><?php echo Text::_('COM_SDAJEM_FIELD_ORGANIZER_LABEL'); ?>: <?php echo $organizer->user->name; ?></h6>
	            <?php endif; ?>
            </div>
            <?php if ($canEdit) : ?>
                <div class="icons">
                    <?php echo HTMLHelper::_('sdajemIcon.edit', $event, $tparams); ?>
                </div>
            <?php endif; ?>
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
	    <?php if (isset($this->location)) : ?>
            <?php
            $status = ($this->activeAccordion === 'event.location') ? 'show' :  '';
            $collapsed = ($this->activeAccordion === 'event.location') ? '' : 'collapsed';
            ?>
        <div class="accordion-item">
            <div class="accordion-header" id="headingLocation">
                <button class="accordion-button <?php echo $collapsed; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLocation" aria-expanded="true" aria-controls="collapseLocation">
                    <h5>
                    <?php
                        echo Text::_('COM_SDAJEM_LOCATION') . ': ';
                        echo $location->title;
                        ?>
                    </h5>
                </button>
            </div>

            <div id="collapseLocation" class="accordion-collapse collapse <?php echo $status; ?>" aria-labelledby="headingLocation" data-bs-parent="#accordionEvent">
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

	    <?php if(isset($event->hostId)) : ?>
            <?php
            $status = ($this->activeAccordion === 'event.host') ? 'show' :  '';
            $collapsed = ($this->activeAccordion === 'event.host') ? '' : 'collapsed';
            ?>
            <div class="accordion-item">
                <div class="accordion-header" id="headingHost">
                    <button class="accordion-button <?php echo $collapsed; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHost" aria-expanded="true" aria-controls="collapseHost">
                        <h5><?php echo Text::_('COM_SDAJEM_FIELD_HOST_LABEL'); ?>: <?php echo $host->getName(); ?></h5>
                    </button>
                </div>
                <div id="collapseHost" class="accordion-collapse collapse <?php echo $status; ?>" aria-labelledby="headingHost" data-bs-parent="#accordionEvent">
                    <div class="accordion-body">
	                    <?php if ($canEdit) : ?>
                            <div class="icons float-end">
                                <?php echo HTMLHelper::_('contacticon.edit', $host, $tparams); ?>
                            </div>
                        <?php endif;?>
                        <p><?php echo $host->get('telephone'); ?></p>
                        <p><?php echo $host->get('email_to'); ?></p>
                        <p><?php echo $host->get('mobile'); ?></p>
                    </div>
                </div>
            </div>
	    <?php endif; ?>

        <?php if ($tparams->get('sda_use_attending') && !$user->guest): ?>
            <?php
            $status = ($this->activeAccordion === 'event.attending') ? 'show' :  '';
            $collapsed = ($this->activeAccordion === 'event.attending') ? '' : 'collapsed';
            ?>
        <div class="accordion-item">
            <div class="accordion-header" id="headingAttendings">
                <button class="accordion-button <?php echo $collapsed; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAttendings" aria-expanded="true" aria-controls="collapseAttendings">
                    <h5><?php echo Text::_('COM_SDAJEM_FIELD_ATTENDINGS_LABEL'); ?>&nbsp;</h5>
                    <?php
                    if ($event->registerUntil)
                        echo ' (' . Text::_('COM_SDAJEM_FIELD_ATTENDINGS_UNTIL') . ' ' . HTMLHelper::date($event->registerUntil,'d.m.Y') . ') <br>';
                    ?>
                </button>
            </div>
            <div id="collapseAttendings" class="accordion-collapse collapse <?php echo $status; ?>" aria-labelledby="headingAttendings" data-bs-parent="#accordionEvent">
                <div class="accordion-body">
                    <?php
                        echo $this->loadTemplate('attendees');
                    ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

	    <?php if ($tparams->get('sda_events_use_fittings') && !$user->guest && isset($this->eventFittings)): ?>
            <?php
            $status = ($this->activeAccordion === 'event.planing') ? 'show' :  '';
            $collapsed = ($this->activeAccordion === 'event.planing') ? '' : 'collapsed';
            ?>

        <div class="accordion-item d-none d-sm-block">
            <div class="accordion-header" id="headingPlaningArea">
                <button class="accordion-button <?php echo $collapsed; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePlaningArea" aria-expanded="true" aria-controls="collapsePlaningArea">
                    <h5><?php echo Text::_('COM_SDAJEM_PLANING_AREA_LABEL'); ?></h5>
                </button>
            </div>
            <div id="collapsePlaningArea" class="accordion-collapse collapse <?php echo $status; ?>" aria-labelledby="headingPlaningArea" data-bs-parent="#accordionEvent">
                <div class="accordion-body">

                    <?php echo $this->loadTemplate('planing'); ?>

                </div>
            </div>
        </div>
        <div class="accordion-item d-sm-none">
            <button class="accordion-button collapsed" type="button"
                    data-joomla-dialog='{"popupType": "iframe", "width":"90vw", "height": "90vh", "src":"index.php?option=com_sdajem&view=event&tmpl=component&layout=planning_modal&id=<?php echo $event->id; ?>"}'>
                <?php echo Text::_('COM_SDAJEM_PLANING_AREA_LABEL'); ?>
            </button>
        </div>

        <?php endif; ?>

	    <?php if ($tparams->get('sda_events_use_comments') && !$user->guest): ?>
            <?php
            $status = ($this->activeAccordion === 'event.comment') ? 'show' :  '';
            $collapsed = ($this->activeAccordion === 'event.comment') ? '' : 'collapsed';
            ?>
            <div class="accordion-item">
                <div class="accordion-header" id="headingCommentArea">
                    <button class="accordion-button <?php echo $collapsed; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCommentArea" aria-expanded="true" aria-controls="collapseCommentArea">
                        <h5><?php echo Text::_('COM_SDAJEM_COMMENT_AREA_LABEL') . ' '; ?></h5>&nbsp;
                        <?php echo ' (' . $this->comments->count() . ')'; ?>
                    </button>
                </div>
                <div id="collapseCommentArea" class="accordion-collapse collapse <?php echo $status; ?>" aria-labelledby="headingCommentArea" data-bs-parent="#accordionEvent">
                    <div class="accordion-body">

					    <?php echo $this->loadTemplate('comments'); ?>

                    </div>
                </div>
            </div>
	    <?php endif; ?>
    </div>
</div>
