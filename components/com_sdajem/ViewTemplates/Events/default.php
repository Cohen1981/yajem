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
use Joomla\CMS\Access\Access;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */

$this->addCssFile('media://com_sdajem/css/style.css');
$items = $this->getItems();

$guest = Factory::getUser()->guest;
$authorised = Access::getAuthorisedViewLevels(JFactory::getUser()->get('id'));

?>
<form action="<?php echo JRoute::_('index.php?option=com_sdajem&view=Events'); ?>" method="post"
      name="adminForm" id="adminForm">

	<div class="sdajem_flex_row">
		<div class="sdajem_cell sdajem_head">
			<i class="far fa-calendar-alt" aria-hidden="true" title="<?php echo JText::_('COM_SDAJEM_ICON_DATE') ?>"></i>
			<?php echo JText::_('COM_SDAJEM_EVENT_DATE_LABEL'); ?>
		</div>
		<div class="sdajem_cell sdajem_head">
			<i class="far fa-bookmark" aria-hidden="true" title="<?php echo JText::_('COM_SDAJEM_ICON_TITLE') ?>">&nbsp;</i>
			<?php echo JText::_('COM_SDAJEM_EVENT_TITLE_LABEL'); ?>
		</div>
		<div class="sdajem_cell sdajem_head">
			<i class="fas fa-map-marker-alt" aria-hidden="true" title="<?php echo JText::_('COM_SDAJEM_ICON_MAP') ?>">&nbsp;</i>
			<?php echo JText::_('COM_SDAJEM_EVENT_SDAJEM_LOCATION_ID_LABEL'); ?>
		</div>
		<div class="sdajem_cell sdajem_head">
			<i class="far fa-flag" aria-hidden="true" title="<?php echo JText::_('COM_SDAJEM_ICON_CATEGORY') ?>"></i>
			<?php echo JText::_('COM_SDAJEM_EVENT_SDAJEM_CATEGORIE_ID_LABEL'); ?>
		</div>
		<div class="sdajem_cell sdajem_head">
			<i class="fas fa-users" aria-hidden="true" title="<?php echo JText::_('COM_SDAJEM_ICON_USERS') ?>"></i>
			<?php echo JText::_('COM_SDAJEM_EVENT_ATTENDEES'); ?>
		</div>

		<?php if (!$guest):	?>
			<div class="sdajem_cell sdajem_head">
				<i class="fas fa-info-circle" aria-hidden="true" title="<?php echo JText::_('COM_SDAJEM_ICON_STATUS') ?>">&nbsp;</i>
				<?php echo JText::_('COM_SDAJEM_EVENT_EVENTSTATUS_LABEL'); ?>
			</div>
		<?php endif; ?>

	</div>

	<?php foreach ($items as $event): ?>
	<?php if (in_array($event->access, $authorised)) : ?>

	<div class="sdajem_flex_row">

		<div class="sdajem_cell">
			<i class="far fa-calendar-alt" aria-hidden="true" title="<?php echo JText::_('COM_SDAJEM_ICON_DATE') ?>"></i>
			<?php
			$startDate = new Date($event->startDateTime);
			$endDate = new Date($event->endDateTime);

			if ((bool) $event->allDayEvent)
			{
				echo $startDate->format('d.m.Y') . " - ";
				echo $endDate->format('d.m.Y');
			}
			else
			{
				echo $startDate->format('d.m.Y H:i') . " - ";
				echo $endDate->format('d.m.Y H:i');
			}
			?>
		</div>
		<div class="sdajem_cell">
			<i class="far fa-bookmark" aria-hidden="true" title="<?php echo JText::_('COM_SDAJEM_ICON_TITLE') ?>">&nbsp;</i>
			<a href="<?php echo Route::_('index.php?option=com_sdajem&task=read&id=' . $event->sdajem_event_id) ?>">
				<?php echo $event->title; ?>
			</a>
		</div>
		<div class="sdajem_cell">
			<i class="fas fa-map-marker-alt" aria-hidden="true" title="<?php echo JText::_('COM_SDAJEM_ICON_MAP') ?>">&nbsp;</i>
			<?php echo $event->location->title; ?>
		</div>
		<div class="sdajem_cell">
			<i class="far fa-flag" aria-hidden="true" title="<?php echo JText::_('COM_SDAJEM_ICON_CATEGORY') ?>"></i>
			<?php echo $event->category->title; ?>
		</div>
		<div class="sdajem_cell">
			<i class="fas fa-users" aria-hidden="true" title="<?php echo JText::_('COM_SDAJEM_ICON_USERS') ?>"></i>
			<?php
			if ((bool) $event->useRegistration)
			{
				echo $event->getAttendingCount();
			}
			?>
		</div>

		<?php if (!$guest):	?>
			<div class="sdajem_cell">
				<i class="fas fa-info-circle" aria-hidden="true" title="<?php echo JText::_('COM_SDAJEM_ICON_STATUS') ?>">&nbsp;</i>
				<?php
				switch ($event->eventStatus)
				{
					case 0:
						echo "<i class=\"fas fa-question-circle\"></i>";
						break;
					case 1:
						echo "<i class=\"fas fa-thumbs-up\" style=\"color: #51a351;\"></i>";
						break;
					case 2:
						echo "<i class=\"fas fa-thumbs-down\" style=\"color: #ff6b6b;\"></i>";
						break;
				}
				?>
			</div>
		<?php endif; ?>

	</div>

	<?php endif; ?>
	<?php endforeach; ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>
