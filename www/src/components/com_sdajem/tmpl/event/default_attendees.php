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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Helper\EventHtmlHelper;
use Sda\Component\Sdajem\Site\Model\EventAttendeeModel;
use Joomla\CMS\Helper\ContentHelper;
use Sda\Component\Sdajem\Site\Model\EventModel;

$canDo   = ContentHelper::getActions('com_sdajem', 'category', $this->item->catid);
$user = Factory::getApplication()->getIdentity();
$tparams = $this->item->params;

/* @var EventModel $event */
$event = $this->item;

?>
<div id="attendings" class="sda_row">
            <h5><?php echo Text::_('COM_SDAJEM_ATTENDESS'); ?></h5>
<div class="sda_row">
	<?php if (isset($event->attendings)) : ?>
		<?php if ($event->eventStatus != EventStatusEnum::PLANING->value ||
			$canDo->get('core.manage') ||
			($canDo->get('core.edit.own') && $event->created_by == $user->id)
		) : ?>
			<div class="sda_attendee_container">
				<?php foreach ($event->attendings as $i => $attending) : ?>
					<?php if ($tparams->get('sda_avatar_field_name') && $tparams->get('sda_use_avatar')): ?>
						<?php EventHtmlHelper::renderAttendee(new EventAttendeeModel($attending), $tparams->get('sda_avatar_field_name')); ?>
					<?php else: ?>
						<?php EventHtmlHelper::renderAttendee(new EventAttendeeModel($attending)); ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
<div class="sda_row">
	<?= HTMLHelper::_('form.token'); ?>
	<?php echo HTMLHelper::_('sdajemIcon.register', $event, $tparams); ?>
</div>
</div>