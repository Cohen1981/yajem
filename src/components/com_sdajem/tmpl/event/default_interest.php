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
use Sda\Component\Sdajem\Administrator\Library\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Helper\EventHtmlHelper;
use Sda\Component\Sdajem\Site\Model\EventInterestModel;

/** @var \Sda\Component\Sdajem\Site\View\Event\HtmlView $this */

$canDo   = ContentHelper::getActions('com_sdajem', 'category', $this->item->catid);
$user = Factory::getApplication()->getIdentity();
$tparams = $this->item->paramsRegistry;

$event = $this->item;

?>
<div id="attendings" class="sda_row">
	<h5><?php echo Text::_('COM_SDAJEM_ATTENDESS'); ?></h5>
	<div class="sda_row">
		<?php if (isset($this->interests)) : ?>
			<?php if ($event->eventStatus != EventStatusEnum::PLANING->value ||
				$canDo->get('core.manage') ||
				($canDo->get('core.edit.own') && $event->created_by == $user->id)
			) : ?>
				<div class="sda_attendee_container">
					<?php foreach ($this->interests as $i => $interested) : ?>
						<?php if ($tparams->get('sda_avatar_field_name') && $tparams->get('sda_use_avatar')): ?>
							<?php EventHtmlHelper::renderInterest(new EventInterestModel($interested), $tparams->get('sda_avatar_field_name')); ?>
						<?php else: ?>
							<?php EventHtmlHelper::renderInterest(new EventInterestModel($interested)); ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
                <div class="sda_row">
                    <h5><?php echo Text::_('COM_SDAJEM_INTEREST_COMMENTS'); ?></h5>
	                <?php foreach ($this->interests as $i => $interested) : ?>
                        <?php
                            $interest = new EventInterestModel($interested);
                            if ($interest->comment)
                            {
	                            echo '<div class="sda_row"><b>' . $interest->user->username . ':</b></br>';
	                            echo $interest->comment . '</div>';
                            }
                        ?>
                    <?php endforeach; ?>
                </div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<div class="sda_row">
		<?= HTMLHelper::_('form.token'); ?>
		<?php echo HTMLHelper::_('sdajemIcon.register', $event, null, $tparams); ?>
	</div>
</div>