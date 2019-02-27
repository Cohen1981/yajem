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
$this->yajemHtmlHelper = \Joomla\Component\Yajem\Administrator\Helpers\EventHtmlHelper::cast($this->yajemHtmlHelper);

$waitingList = (!$this->attendeeNumber < $this->event->registrationLimit && (bool) $this->event->useWaitingList);

$regPossible = false;

// Only logged in Users should be able to register for an event
if (!$this->eventParams->isGuest)
{
	$regUntil = Factory::getDate($this->event->registerUntil, 'UTC');
	$currentDate = Factory::getDate('now', 'UTC');

	if ($currentDate < $regUntil || !(bool) $this->event->userRegisterUntil)
	{
		if ($this->event->registrationLimit == 0 || $this->attendeeNumber < $this->event->registrationLimit || $waitingList)
		{
			$regPossible = true;
		}
	}
}

?>

<?php if ((bool) $this->event->useRegisterUntil): ?>
<div id="organizer" class="yajem_grid_section">
	<div class="yajem_label"><?php echo JText::_('COM_YAJEM_REGISTER_UNTIL'); ?></div>
	<div class="yajem_output"><?php echo $this->event->registerUntil; ?></div>
</div>
<?php endif; ?>

<div id="yajem_attendees" class="yajem_flex_row">

<!-- Guest will only see number of attending persons -->
<?php
if (!$guest)
{
	if (count($this->attendees) > 0)
	{
		foreach ($this->attendees as $i => $item)
		{
			echo $this->yajemHtmlHelper->getAttendingHtml($item->attendee['id'], $item->status, $item->id);
		}
	}
}
?>

</div>

<div class="yajem_line"></div>

<?php if ($regPossible): ?>

	<form action="<?php echo JRoute::_('index.php?option=com_yajem&view=events'); ?>"  name="adminForm" id="adminForm" method="post">
		<!--<div class="yajem_grid_section">-->
			<div id="reg_buttons" class="yajem_flex_row yajem-button-group">
				<?php echo $this->yajemHtmlHelper->getRegLinksAttendee($this->eventParams->userId); ?>
			</div>
			<!--<?php echo $this->yajemHtmlHelper->getEquipmentHtml($this->eventParams->userId); ?>
		</div>-->

		<input type="radio" class="yajem_hidden" id="reg" name="register" value="reg" onchange="adminForm.submit()" />
		<input type="radio" class="yajem_hidden" id="regw" name="register" value="regw" onchange="adminForm.submit()" />
		<input type="radio" class="yajem_hidden" id="unreg" name="register" value="unreg" onchange="adminForm.submit()" />
		<input type="hidden" id="attendees_id" name="id" value="<?php echo $this->attendees[$this->eventParams->userId]->id; ?>">
		<input type="hidden" id="attendees_userId" name="userId" value="<?php echo $this->eventParams->userId; ?>" />
		<input type="hidden" id="attendees_eventId" name="eventId" value="<?php echo $this->event->id; ?>" />
		<input type="hidden" name="task" value="event.changeAttendingStatus" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</form>

<?php endif; ?>
