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
use Sda\Component\Sdajem\Site\Model\AttendingsModel;

\defined('_JEXEC') or die;

/* @var \Sda\Component\Sdajem\Site\Model\EventModel $item */
/* @var \Sda\Component\Sdajem\Site\View\Events\HtmlView $this */

$wa=$this->document->getWebAssetManager();
$wa->registerAndUseStyle('sdajem', 'com_sdajem/sdajem.css');

$params = $this->get('State')->get('params');
$canDo = ContentHelper::getActions('com_sdajem');
$user  = Factory::getApplication()->getIdentity();
?>

<form action="<?php echo Route::_('index.php?option=com_sdajem'); ?>" method="post" name="adminForm" id="adminForm">
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
	<?php echo HTMLHelper::_('form.token'); ?>
    <div>
	    <?php if ($canDo->get('core.create')) : ?>
            <div class="mb-2">
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('event.add')">
                    <span class="fas fa-check" aria-hidden="true"></span>
				    <?php echo Text::_('COM_SDAJEM_EVENT_ADD'); ?>
                </button>
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('location.add')">
                    <span class="fas fa-check" aria-hidden="true"></span>
				    <?php echo Text::_('COM_SDAJEM_LOCATION_ADD'); ?>
                </button>
            </div>
	    <?php endif; ?>
    </div>
</form>
<?php foreach ($this->items as $i => $item) : ?>

<div class="sda_row">
    <div class="sda_event_head">
        <div>
            <a href="<?php echo Route::_('index.php?option=com_sdajem&view=event&task=read&id=' . $item->id) ?>">
                <?php echo $this->escape($item->title). ': ';
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
            </a>
        </div>
        <form action="<?php echo Route::_('index.php?option=com_sdajem&id=' . (int) $item->id); ?>"
              method="post"
              name="editForm<?php echo $i; ?>"
              id="editForm<?php echo $i; ?>">
            <input type="hidden" name="id" value="<?php echo $item->id; ?>">
        <div class="sda_icons">
	        <?php if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) : ?>
                <div class="icons">
			        <?php echo HTMLHelper::_('eventicon.edit', $item, $params); ?>
                </div>
                <!--<a href="<?php /*echo Route::_('index.php?option=com_sdajem&task=event.edit&id=' . $item->id) */?>">EDIT</a> -->
	        <?php endif; ?>
        </div>
        </form>
    </div>
    <div>
        <?php echo $item->location_name ?>
    </div>
</div>
<?php endforeach; ?>

