<?php
/**
 * @package     Yajem.Site
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

$user       = Factory::getUser();
$guest		= $user->guest;
$userId     = $user->get('id');
$useReg = (bool) $this->event->useRegistration;
$googleApiKey = (string) JComponentHelper::getParams('com_yajem')->get('global_googleapi');
$useOrgG = (bool) JComponentHelper::getParams('com_yajem')->get('use_organizer');
if ($useOrgG)
{
	$useOrg = (bool) $this->event->useOrganizer;
}

if ($useOrg)
{
	$symbolOpen = '<i class="fas fa-question-circle" aria-hidden="true" title="' . JText::_("COM_YAJEM_EVENT_STATUS_OPEN") . '"></i>';
	$symbolConfirmed = '<i class="far fa-thumbs-up" aria-hidden="true" title="' . JText::_("COM_YAJEM_EVENT_STATUS_CONFIRMED") . '"></i>';
	$symbolCanceled = '<i class="far fa-thumbs-down" aria-hidden="true" title="' . JText::_("COM_YAJEM_EVENT_STATUS_CANCELED") . '"></i>';
	$linkConfirm = '<label id="yajem_confirm" class="green" for="eConfirm">' . $symbolConfirmed . '</label>';
	$linkCancel = '<label id="yajem_canc" class="crimson" for="eCancel">' . $symbolCanceled . '</label>';

	switch ($this->event->eventStatus)
	{
		case 0:
			$eventStatus = $symbolOpen;
			break;
		case 1:
			$eventStatus = $symbolConfirmed;
			break;
		case 2:
			$eventStatus = $symbolCanceled;
			break;
	}
}

?>

<div id="yajem_container">

	<!-- Event Basics Section -->
	<div class="yajem_switch_container">
		<div class="yajem_section_header yajem_bottom-rounded">
			<div class="yajem_inline-block">
				<h2>
					<?php echo $this->event->title . '&nbsp;&nbsp;'; ?>
					<?php if ($useOrg && !$guest): ?>
						<?php echo $eventStatus; ?>
					<?php endif; ?>
				</h2>
            </div>
            <div class="yajem_inline-block">
				<?php if ($userId == $this->event->organizer->user_id): ?>
                    <form action="<?php echo JRoute::_('index.php?option=com_yajem&view=events'); ?>"  name="eventForm" class="yajem-no-margin" id="eventForm" method="post">
                        <div class="yajem_flex_row" id="eventStatus">
							<?php
							switch ($this->event->eventStatus)
							{
								case 0:
									echo $linkConfirm;
									echo $linkCancel;
									break;
								case 1:
									echo $linkCancel;
									break;
								case 2:
									echo $linkConfirm;
									break;
							}
							?>
                            <input type="radio" class="yajem_hidden" id="eConfirm" name="eStatus" value="confirm" onchange="eventForm.submit()" />
                            <input type="radio" class="yajem_hidden" id="eCancel" name="eStatus" value="cancel" onchange="eventForm.submit()" />
                            <input type="hidden" name="eventId" value="<?php echo $this->event->id; ?>" />
                            <input type="hidden" name="task" value="event.changeEventStatus" />
                        </div>
                    </form>
				<?php endif; ?>
			</div>
			<label id="basic-section-button" class="yajem_switch" for="yajem_switch_basic">
				<i class="far fa-plus-square" aria-hidden="true" title="<?php echo JText::_('COM_YAJEM_TOGGLE') ?>"></i>
			</label>
		</div>
		<input type="checkbox" id="yajem_switch_basic" class="yajem_hidden" checked="checked"/>
		<div class="yajem_section_container yajem_switchable">

			<?php echo $this->loadTemplate('event'); ?>

		</div>
	</div>

	<!-- Location -->
	<div class="yajem_switch_container">
		<div class="yajem_section_header yajem_bottom-rounded">
			<div class="yajem_inline-block">
				<h2>
					<?php echo $this->location->title; ?>
				</h2>
			</div>
			<label id="location-section-button" class="yajem_switch" for="yajem_switch_location">
				<i class="far fa-plus-square" aria-hidden="true" title="<?php echo JText::_('COM_YAJEM_TOGGLE') ?>"></i>
			</label>
		</div>
		<input type="checkbox" id="yajem_switch_location" class="yajem_hidden"/>
		<div class="yajem_section_container yajem_switchable">

			<?php echo $this->loadTemplate('location'); ?>

		</div>
	</div>

	<!-- Google Map if API-Key provided -->
	<?php if ($googleApiKey): ?>
		<div class="yajem_switch_container">
			<div class="yajem_section_header yajem_bottom-rounded">
				<div class="yajem_inline-block">
					<h2>
						<?php echo JText::_('COM_YAJEM_MAP_TITLE'); ?>
					</h2>
				</div>
				<label id="map-section-button" class="yajem_switch" for="yajem_switch_map">
					<i class="far fa-plus-square" aria-hidden="true" title="<?php echo JText::_('COM_YAJEM_TOGGLE') ?>"></i>
				</label>
			</div>
			<input type="checkbox" id="yajem_switch_map" class="yajem_hidden"/>
			<div id="map" class="yajem_section_container yajem_switchable">
				<?php
				$googleAddress = $this->location->street . ", " . $this->location->postalCode . " " . $this->location->city;
				?>
				<iframe id="google_map"
						frameborder="0" style="border:0"
						src="https://www.google.com/maps/embed/v1/place?key=<?php echo $googleApiKey; ?>&q=<?php echo $googleAddress ?>"
						allowfullscreen>
				</iframe>
			</div>
		</div>
	<?php endif; ?>

	<!-- Registration if used -->
	<?php if ($useReg): ?>
		<div class="yajem_switch_container">
			<div class="yajem_section_header yajem_bottom-rounded">
				<div class="yajem_inline-block">
					<h2>
						<?php echo JText::_('COM_YAJEM_TITLE_ATTENDEES') . "&nbsp;"; ?>
						<?php echo ('<i class="fas fa-users" aria-hidden="true"></i>&nbsp;' . $this->attendeeNumber); ?>
					</h2>
				</div>
				<?php if (!$guest):?>
				<label id="registration-section-button" class="yajem_switch" for="yajem_switch_reg">
					<i class="far fa-plus-square" aria-hidden="true" title="<?php echo JText::_('COM_YAJEM_TOGGLE') ?>"></i>
				</label>
				<?php endif; ?>
			</div>
			<input type="checkbox" id="yajem_switch_reg" class="yajem_hidden"/>
			<div class="yajem_section_container yajem_switchable">

				<?php echo $this->loadTemplate('registration'); ?>

			</div>
		</div>
	<?php endif; ?>

</div>
