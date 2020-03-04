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
use Joomla\CMS\Language\Text;
use Sda\Jem\Admin\Helper\EventStatusHelper;
use Sda\Jem\Admin\Helper\IconHelper;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */

$this->addCssFile('media://com_sdajem/css/style.css');
$currentDate = new Date;

/** @var \Sda\Jem\Site\Model\Event $model */
$model = $this->getModel();

// Show only future events sorted ascending
$items = $model->where('startDateTime', '>=', $currentDate->toSql())
	->where('enabled', '=', 1)
	->orderBy('startDateTime')->get();

$guest = Factory::getUser()->guest;

?>
<form action="<?php echo JRoute::_('index.php?option=com_sdajem&view=Events'); ?>" method="post"
      name="adminForm" id="adminForm">

	<div class="sdajem_flex_row">
		<div class="sdajem_cell sdajem_head">
			<div class="sdajem_align_help">
				<?php echo IconHelper::dateIcon(); ?>
				<?php echo Text::_('COM_SDAJEM_EVENT_DATE_LABEL'); ?>
			</div>
		</div>
		<div class="sdajem_cell sdajem_head">
			<div class="sdajem_align_help">
				<?php echo IconHelper::titleIcon(); ?>
				<?php echo Text::_('COM_SDAJEM_EVENT_TITLE_LABEL'); ?>
			</div>
		</div>
		<div class="sdajem_cell sdajem_head">
			<div class="sdajem_align_help">
				<?php echo IconHelper::locationIcon(); ?>
				<?php echo Text::_('COM_SDAJEM_EVENT_SDAJEM_LOCATION_ID_LABEL'); ?>
			</div>
		</div>
		<div class="sdajem_cell sdajem_head">
			<div class="sdajem_align_help">
				<?php echo IconHelper::categoryIcon(); ?>
				<?php echo Text::_('COM_SDAJEM_EVENT_SDAJEM_CATEGORY_ID_LABEL'); ?>
			</div>
		</div>
		<div class="sdajem_cell sdajem_head">
			<div class="sdajem_align_help">
				<?php echo IconHelper::usersIcon(); ?>
				<?php echo Text::_('COM_SDAJEM_EVENT_ATTENDEES'); ?>
			</div>
		</div>

		<?php if (!$guest):	?>
			<div class="sdajem_cell sdajem_head">
				<div class="sdajem_align_help">
					<?php echo IconHelper::statusIcon(); ?>
					<?php echo Text::_('COM_SDAJEM_EVENT_EVENTSTATUS_LABEL'); ?>
				</div>
			</div>
		<?php endif; ?>

	</div>

	<?php foreach ($items as $event): ?>
	<?php $event->applyAccessFiltering() ?>

	<div class="sdajem_flex_row">

		<div class="sdajem_cell">
			<div class="sdajem_align_help">
				<?php echo IconHelper::dateIcon(); ?>

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
		</div>
		<div class="sdajem_cell">
			<div class="sdajem_align_help">
				<?php echo IconHelper::titleIcon(); ?>
				<a href="<?php echo Route::_('index.php?option=com_sdajem&task=read&id=' . $event->sdajem_event_id) ?>">
					<?php echo $event->title; ?>
				</a>
			</div>
		</div>
		<div class="sdajem_cell">
			<div class="sdajem_align_help">
				<?php echo IconHelper::locationIcon(); ?>
				<?php echo $event->location->title; ?>
			</div>
		</div>
		<div class="sdajem_cell">
			<div class="sdajem_align_help">
				<?php echo IconHelper::categoryIcon(); ?>
				<?php echo $event->category->title; ?>
			</div>
		</div>
		<div class="sdajem_cell">
			<div class="sdajem_align_help">
				<?php echo IconHelper::usersIcon(); ?>
				<?php
				if ((bool) $event->useRegistration)
				{
					echo $event->getAttendingCount();
				}
				?>
			</div>
		</div>

		<?php if (!$guest):	?>
			<div class="sdajem_cell">
				<div class="sdajem_align_help">
					<?php echo IconHelper::statusIcon(); ?>
					<?php
						echo EventStatusHelper::getSymbolByStatus($event->eventStatus);
					?>
				</div>
			</div>
		<?php endif; ?>

	</div>

	<?php endforeach; ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>
