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

$user	= Factory::getUser();
$userId = $user->get('id');
$guest	= $user->guest;
$waitingList = (!$this->attendeeNumber < $this->event->registrationLimit && (bool) $this->event->useWaitingList);

$regPossible = false;

// Only logged in Users should be able to register for an event
if (!$guest)
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

<?php if ($this->event->registerUntil): ?>
<div id="organizer" class="yajem_grid_section">
    <div class="yajem_label"><?php echo JText::_('COM_YAJEM_REGISTER_UNTIL'); ?></div>
    <div class="yajem_output"><?php echo $this->event->registerUntil; ?></div>
</div>
<?php endif; ?>

<div id="yajem_attendees" class="yajem_flex_row">
	<?php
	// Guest will only see number of attending persons
	if (!$guest)
	{
		foreach ($this->attendees as $i => $item)
		{
			echo ('<div class="yajem_attendee yajem_flex_row">');

			echo ('<div class="yajem_user">');

			if ($item->avatar)
			{
				echo '<div class="yajem_avatar_container"><img class="yajem_avatar" src="' . $item->avatar . '"/></div>';
			}
			if ($item->clearName)
			{
				$userName = $item->clearName;
			}
			else
			{
				$userName = $item->attendee;
			}

			echo ('<div class="yajem_uname">' . $userName . '</div>');

			echo ('</div>');

			echo ('<div class="yajem_att_status">');
			switch ($item->status)
			{
				case 0:
					echo ('<div class="yajem_ustatus">
							<i class="fas fa-question-circle" aria-hidden="true"></i> ' . JText::_("COM_YAJEM_NOT_DECIDED") . '
							</div>'
					);
					break;
				case 1:
					echo ('<div class="yajem_ustatus">
							<i class="far fa-thumbs-up" aria-hidden="true"></i> ' . JText::_("COM_YAJEM_ATTENDING") . '
							</div>'
					);
					break;
				case 2:
					echo ('<div class="yajem_ustatus">
							<i class="far fa-thumbs-down" aria-hidden="true"></i> ' . JText::_("COM_YAJEM_NOT_ATTENDING") . '
							</div>'
					);
					break;
				case 3:
					echo ('<div class="yajem_ustatus">
							<i class="far fa-clock" aria-hidden="true"></i> ' . JText::_("COM_YAJEM_ON_WAITINGLIST") . '
							</div>'
					);
					break;
			}

			echo ('<div class="yajem_att_comment"><i class="far fa-comment" aria-hidden="true"> ' . $item->comment . '</i></div>');

			echo ('</div>');
			echo ('</div>');
		}
	}
	?>
</div>

<div class="yajem_line"></div>

<?php if ($regPossible): ?>

	<form action="<?php echo JRoute::_('index.php?option=com_yajem&view=events'); ?>"  name="adminForm" id="adminForm" method="post">
		<div class="yajem_flex_row yajem-button-group">
			<?php
			if ($waitingList)
			{
				$regButton = '<label id="yajem_reg" class="yajem_css_switch yajem_rounded" for="regw">' . JText::_('COM_YAJEM_REGW') . '</label>';
			}
			else
			{
				$regButton = '<label id="yajem_reg" class="yajem_css_switch yajem_rounded" for="reg">' . JText::_('COM_YAJEM_REG') . '</label>';
			}

			$unregButton = '<label id="yajem_unreg" class="yajem_css_switch yajem_rounded" for="unreg">' . JText::_('COM_YAJEM_UNREG') . '</label>';

			switch ($this->attendees[$userId]->status)
			{
				case 0:
					echo $regButton;
					echo $unregButton;
					break;
				case 1:
					echo $unregButton;
					break;
				case 2:
					echo $regButton;
					break;
				case 3:
					echo $unregButton;
					break;
			}
			?>
		</div>
		<div class="yajem_flex_row" id="yajem_comment_line">
			<div class="yajem_label"><?php echo JText::_('COM_YAJEM_ATTENDEE_COMMENT') . ' &nbsp;';?></div>
			<input type="text" class="yajem_comment input-xxlarge input-large-text" id="comment" name="comment" value=""/>
		</div>

		<input type="radio" class="yajem_hidden" id="reg" name="register" value="reg" onchange="adminForm.submit()" />
		<input type="radio" class="yajem_hidden" id="regw" name="register" value="regw" onchange="adminForm.submit()" />
		<input type="radio" class="yajem_hidden" id="unreg" name="register" value="unreg" onchange="adminForm.submit()" />
		<input type="hidden" name="id" value="<?php echo $this->attendees[$userId]->id; ?>">
		<input type="hidden" name="userId" value="<?php echo $userId; ?>" />
		<input type="hidden" name="eventId" value="<?php echo $this->event->id; ?>" />
		<input type="hidden" name="task" value="event.changeAttendingStatus" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</form>

<?php endif; ?>
