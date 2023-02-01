<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

\defined('_JEXEC') or die;

/* @var \Sda\Component\Sdajem\Site\Model\EventModel $item */
/* @var \Sda\Component\Sdajem\Site\View\Events\HtmlView $this */

$params = $this->get('State')->get('params');
$canDo = ContentHelper::getActions('com_sdajem');
$user  = Factory::getApplication()->getIdentity();
?>

<form action="<?= Route::_('index.php?option=com_sdajem&view=event') ?>" method="post" id="adminForm" name="adminForm">
	<?= HTMLHelper::_('form.token'); ?>
    <input type="hidden" name="option" value="com_sdajem">
    <input type="hidden" name="task" value="" />
    <div>
	    <?php if ($canDo->get('core.create')) : ?>
            <a href="<?php echo Route::_('index.php?option=com_sdajem&task=event.add') ?>">
                <?php echo TEXT::_('COM_SDAJEM_EVENT_ADD') ?>
            </a>
	    <?php endif; ?>
    </div>
<?php foreach ($this->items as $i => $item) : ?>
	<tr class="row<?php echo $i % 2; ?>">
		<th scope="row" class="has-context">
            <a href="<?php echo Route::_('index.php?option=com_sdajem&view=event&task=read&id=' . $item->id) ?>">
                <div>
                    <?php echo $this->escape($item->title);
                        if ($item->allDayEvent) {
                            echo HTMLHelper::date($item->startDateTime,'d.m.Y',true);
                            echo ' - ';
	                        echo HTMLHelper::date($item->endDateTime,'d.m.Y',true);
                        } else {
	                        echo HTMLHelper::date($item->startDateTime,'d.m.Y H:i',true);
	                        echo ' - ';
	                        echo HTMLHelper::date($item->endDateTime,'d.m.Y H:i',true);
                        }
                    ?>
                </div>
                <div>
                    <?php if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) : ?>
                        <a href="<?php echo Route::_('index.php?option=com_sdajem&task=event.edit&id=' . $item->id) ?>">EDIT</a>
                    <?php endif; ?>
                </div>
            </a>
		</th>
		<td class="d-none d-md-table-cell">
			<?php echo $item->description; ?>
		</td>
	</tr>
<?php endforeach; ?>
</form>
