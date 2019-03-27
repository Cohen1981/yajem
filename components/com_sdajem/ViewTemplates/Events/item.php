<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Factory;
use FOF30\Date\Date;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

/** @var \Sda\Jem\Site\View\Event\Html  $this       */
/** @var \Sda\Jem\Site\Model\Event      $event      */
/** @var \Sda\Jem\Site\Model\Attendee   $attendee   */
/** @var \Sda\Jem\Site\Model\Comment    $comment    */

$this->addCssFile('media://com_sdajem/css/style.css');
$event = $this->getItem();

$startDate = new Date($event->startDateTime);
$endDate = new Date($event->endDateTime);

$guest = Factory::getUser()->guest;
?>

<div class="sdajem_content_container">

<div class="sdajem_event_grid">

	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_EVENT_TITLE_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php echo $event->title; ?>
	</div>
	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_EVENT_STARTDATETIME_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php
		if ((bool) $event->allDayEvent)
		{
			echo $startDate->format('d.m.Y');
		}
		else
		{
			echo $startDate->format('d.m.Y H:i');
		}
		?>
	</div>
	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_EVENT_ENDDATETIME_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php
		if ((bool) $event->allDayEvent)
		{
			echo $endDate->format('d.m.Y');
		}
		else
		{
			echo $endDate->format('d.m.Y H:i');
		}
		?>
	</div>
	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_EVENT_URL_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php echo $event->url; ?>
	</div>
	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_EVENT_DESCRIPTION_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php echo $event->description; ?>
	</div>

</div>

<!-- Location Block -->

<div class="well">
	<div class="titleContainer">
		<h2 class="page-title">
			<span class="icon-generic" aria-hidden="true"></span>
			<?php echo Text::_('COM_SDAJEM_TITLE_LOCATION_BASIC'); ?>
		</h2>
	</div>
	<div class="buttonsContainer">
		<!--<a class="btn btn-small button-new btn-success"
		   href="index.php?option=com_sdaprofiles&view=fittings&task=add&profileId=<?php //echo $profile->sdaprofiles_profile_id ?>"
		>
			<i class="icon-new icon-white"></i>
			<?php //echo Text::_('JNEW'); ?>
		</a>-->
	</div>
</div>

<div class="sdajem_event_grid">

	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_LOCATION_TITLE_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php echo $event->location->title; ?>
	</div>
	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_LOCATION_URL_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php echo $event->location->url; ?>
	</div>
	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_LOCATION_ADDRESS_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php echo $event->location->street . "<br/>"; ?>
		<?php echo $event->location->postalCode . " "; ?>
		<?php echo $event->location->city; ?>
	</div>
	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_LOCATION_DESCRIPTION_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php echo $event->location->description; ?>
	</div>
	<div class="sdajem_label">
		<?php echo Text::_('COM_SDAJEM_LOCATION_CONTACT_LABEL'); ?>
	</div>
	<div class="sdajem_value">
		<?php echo $event->location->contact->name; ?>
	</div>

</div>

<!-- Attendee Block -->

<div class="well">
	<div class="titleContainer">
		<h2 class="page-title">
			<span class="icon-generic" aria-hidden="true"></span>
			<?php echo Text::_('COM_SDAJEM_TITLE_ATTENDEES_BASIC'); ?>
			<span>&nbsp;</span>
			<i class="fas fa-users" aria-hidden="true"></i>
			<?php
				echo $event->attendees->count();
			?>
		</h2>
	</div>
	<div class="buttonsContainer">
		<?php if (!$guest): ?>
		<form action="<?php echo JRoute::_('index.php?option=com_sdajem&task=registerAttendee'); ?>" method="post"
		      name="attendeeForm" id="attendeeForm">

			<?php echo $event->getRegisterHtml(); ?>

			<input type="hidden" name="eventId" value="<?php echo $event->sdajem_event_id ?>"/>
		</form>
		<?php endif; ?>
	</div>
</div>

<?php if (!$guest): ?>
<div class="sdajem_flex_row">

	<?php
	foreach ($event->attendees as $attendee)
	{
			echo $attendee->getAttendingHtml();
	}
	?>

</div>
<?php endif; ?>

<!-- Comment Block -->
<?php if (!$guest): ?>
<form action="<?php echo JRoute::_('index.php?option=com_sdajem&task=comment'); ?>" method="post"
      name="commentForm" id="commentForm">
	<div class="well">
		<div class="titleContainer">
			<h2 class="page-title">
				<span class="icon-generic" aria-hidden="true"></span>
				<?php echo Text::_('COM_SDAJEM_TITLE_COMMENTS_BASIC'); ?>
			</h2>
		</div>
		<div class="buttonsContainer">
			<button type="submit"
			       value="<?php echo Text::_('SDAJEM_NEW_COMMENT'); ?>"
			       form="commentForm"
			><?php echo Text::_('SDAJEM_NEW_COMMENT'); ?></button>
		</div>
	</div>

	<textarea form="commentForm" id="comment" wrap="soft" name="comment"></textarea>

	<div class="sdajem_comment_container">

		<?php
		foreach ($event->comments->sortByDesc('timestamp') as $comment)
		{
			echo $comment->getCommentHtml();
		}
		?>

	</div>
	<input type="hidden" name="eventId" value="<?php echo $event->sdajem_event_id ?>"/>
</form>
<?php endif; ?>

</div>
