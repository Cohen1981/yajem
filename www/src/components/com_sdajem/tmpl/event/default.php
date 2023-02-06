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
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
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

<div class="sda_row">
    <div class="sda_event_head">
        <div>
            <?php echo $this->escape($event->title). ': ';
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

<?php if (isset($event->location)) : ?>
<div class="sda_row">
    <?php echo $location->title; ?>
</div>
<?php endif; ?>

<div class="sda_row">
    <?php echo $event->description; ?>
</div>

<?php if($tparams->get('sda_use_organizer') && isset($organizer)) : ?>
    <div class="sda_row">
        <?php echo $organizer->user->name; ?>
    </div>
<?php endif; ?>

<?php if(isset($event->hostId)) : ?>
    <div class="sda_row">
        <?php echo $host->get('name'); ?>
    </div>
<?php endif; ?>

<?php if ($tparams->get('sda_use_attending')) : ?>
    <div id="attendings" class="sda_row">
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