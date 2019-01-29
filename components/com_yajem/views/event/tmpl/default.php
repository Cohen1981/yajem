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

use Joomla\CMS\Language\Text;

if ($useOrg)
{
	switch ($this->event->eventStatus)
	{
		case 0:
			$eventStatus = $this->eventSymbols->open;
			break;
		case 1:
			$eventStatus = $this->eventSymbols->confirmed;
			break;
		case 2:
			$eventStatus = $this->eventSymbols->canceled;
			break;
	}
}

?>

<div id="yajem_container">

        <div class="yajem_section_container">
            <a onclick="getIcs(<?php echo $this->event->id; ?>)"
                    class="hasPopover"
                    data-content="<?php echo Text::_('COM_YAJEM_ICS_DOWNLOAD_DESC'); ?>"
                    data-original-title="<?php echo Text::_('COM_YAJEM_ICS_DOWNLOAD'); ?>">
                <i class="fas fa-file-download" aria-hidden="true"></i>
            </a>
	        <?php if ($canEdit) : ?>
                <div class="yajem_inline-block">
                    <a href="<?php echo JRoute::_('index.php?option=com_yajem&task=editevent.edit&id=') . $this->event->id; ?>">
                        <i class="fas fa-edit" aria-hidden="true"></i>
				        <?php echo Text::_('COM_YAJEM_EDIT_EVENT'); ?>
                    </a>
                </div>
	        <?php endif; ?>
        </div>

	<!-- Event Basics Section -->
	<div class="yajem_switch_container">
		<div class="yajem_section_header yajem_bottom-rounded">
			<div class="yajem_inline-block">
				<h2>
                    <?php
                    if ($this->event->url) {
                        echo '<a href="'. $this->event->url. '" target="_blank">' . $this->event->title . '</a>&nbsp;&nbsp;';
                    } else {
	                    echo $this->event->title . '&nbsp;&nbsp;';
                    }
                    if ($this->eventParams->useOrg && !$this->eventParams->guest) {
                        echo $eventStatus;
                    }
                    ?>
				</h2>
            </div>
            <div class="yajem_inline-block">
				<?php if ($userId == $this->event->organizerId): ?>
                    <form action="<?php echo JRoute::_('index.php?option=com_yajem&view=events'); ?>"  name="eventForm" class="yajem-no-margin" id="eventForm" method="post">
                        <div class="yajem_flex_row" id="eventStatus">
							<?php
							switch ($this->event->eventStatus)
							{
								case 0:
									echo $this->eventLinks->confirm;
									echo $this->eventLinks->cancel;
									break;
								case 1:
									echo $this->eventLinks->cancel;
									break;
								case 2:
									echo $this->eventLinks->confirm;
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
                    <?php
                    if ($this->location->url) {
                        echo '<a href="'. $this->location->url. '" target="_blank">' . $this->location->title . '</a>&nbsp;&nbsp;';
                    } else {
	                    echo $this->location->title . '&nbsp;&nbsp;';
                    }
                    ?>
				</h2>
			</div>
			<label id="location-section-button" class="yajem_switch" for="yajem_switch_location">
				<i class="far fa-plus-square" aria-hidden="true" title="<?php echo JText::_('COM_YAJEM_TOGGLE') ?>"></i>
			</label>
		</div>
		<input type="checkbox" id="yajem_switch_location" class="yajem_hidden" checked="checked"/>
		<div class="yajem_section_container yajem_switchable">

			<?php echo $this->loadTemplate('location'); ?>

		</div>
	</div>

	<!-- Google Map if API-Key provided -->
	<?php if ($this->eventParams->googleApiKey): ?>
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
						src="https://www.google.com/maps/embed/v1/place?key=<?php echo $this->eventParams->googleApiKey; ?>&q=<?php echo $googleAddress ?>"
						allowfullscreen>
				</iframe>
			</div>
		</div>
	<?php endif; ?>

	<!-- Registration if used -->
	<?php if ($this->eventParams->useReg): ?>
		<div class="yajem_switch_container">
			<div class="yajem_section_header yajem_bottom-rounded">
				<div class="yajem_inline-block">
					<h2>
						<?php echo JText::_('COM_YAJEM_TITLE_ATTENDEES') . "&nbsp;"; ?>
						<?php echo ('<i class="fas fa-users" aria-hidden="true"></i>&nbsp;' . $this->attendeeNumber); ?>
					</h2>
				</div>
				<?php if (!$this->eventParams->guest):?>
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

    <!-- Comments if used and registered user -->
	<?php if (!$this->eventParams->guest && $this->eventParams->useComments): ?>
        <div class="yajem_switch_container">
            <div class="yajem_section_header yajem_bottom-rounded">
                <div class="yajem_inline-block">
                    <h2>
						<?php echo JText::_('COM_YAJEM_TITLE_COMMENTS') . "&nbsp;"; ?>
						<?php echo ('<i class="fas fa-comment" aria-hidden="true"></i>&nbsp;' . $this->commentCount); ?>
                    </h2>
                </div>
                <label id="comment-section-button" class="yajem_switch" for="yajem_switch_comment">
                    <i class="far fa-plus-square" aria-hidden="true" title="<?php echo JText::_('COM_YAJEM_TOGGLE') ?>"></i>
                </label>
            </div>
            <input type="checkbox" id="yajem_switch_comment" class="yajem_hidden"/>
            <div class="yajem_section_container yajem_switchable">

				<?php echo $this->loadTemplate('comments'); ?>

            </div>
        </div>
	<?php endif; ?>

</div>
