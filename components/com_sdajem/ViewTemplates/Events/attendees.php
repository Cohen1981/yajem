<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use FOF30\Container\Container;

/** @var \Sda\Jem\Site\View\Event\Html      $this       */
/** @var \Sda\Jem\Site\Model\Event          $event      */

$event = $this->getModel('Event');
$this->addCssFile('media://com_sdajem/css/style.css');
$this->addJavascriptFile('media://com_sdajem/js/attendees.js');

?>
<form name="attendeeForm" id="attendeeForm">
	<div class="well">
		<div class="titleContainer">
			<h2 class="page-title">
				<span class="icon-users" aria-hidden="true"></span>
				<?php echo Text::_('COM_SDAJEM_TITLE_ATTENDEES_BASIC'); ?>
				<span>&nbsp;</span>
				<i class="fas fa-users" aria-hidden="true"></i>
				<span id="attendeeCount">
					<?php echo $event->getAttendingCount(); ?>
				</span>
			</h2>
		</div>

		<div class="buttonsContainer">
		<?php if (!Factory::getUser()->guest && $event->isRegistrationPossible()): ?>

			<?php echo $this->loadAnyTemplate('site:com_sdajem/Event/register'); ?>
			<input type="hidden" name="eventId" value="<?php echo $event->sdajem_event_id ?>"/>
		<?php endif; ?>
		</div>

	</div>
</form>
<?php if (!Factory::getUser()->guest): ?>
<div id="sdajem_attendee_area" class="sdajem_flex_row">

	<?php
	foreach ($event->attendees as $attendee)
	{
		$this->setModel('Attendee', $attendee);
		echo $this->loadAnyTemplate('site:com_sdajem/Attendee/attendee');
	}
	?>

</div>
<?php endif; ?>