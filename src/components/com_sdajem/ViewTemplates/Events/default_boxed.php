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
use Joomla\CMS\Language\Text;
use Sda\Jem\Admin\Helper\EventStatusHelper;
use Sda\Jem\Admin\Helper\IconHelper;
use Joomla\CMS\Router\Route;

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

<?php foreach ($items as $event): ?>
<?php $event->applyAccessFiltering() ?>

<a class="no_decoration" href="<?php echo Route::_('index.php?option=com_sdajem&task=read&id=' . $event->sdajem_event_id) ?>">
    <div class="sdajem_event_box">

        <div class="sdajem_image_container flex_col">
            <div class="sdajem_event_box_date">
                <p>
			        <?php echo IconHelper::dateIcon() ?>
			        <?php echo $event->getFormatedStartDate() ?>
                    <i class="fas fa-angle-double-right" aria-hidden="true"></i>
			        <?php echo $event->getFormatedEndDate() ?>
                </p>
            </div>

            <?php
            if ($event->image)
            {
	            echo "<img class='sdajem_round_img' src=" . $event->image . " alt=" . Text::_('COM_SDAJEM_EVET_IMAGE') ." />";
            }
            ?>
        </div>

        <div class="sdajem_event_details flex_col">
            <div class="sdajem_event_box_title">
                    <?php echo $event->title ?>
	                <?php if (!$guest):	?>
                        <?php echo IconHelper::statusIcon(); ?>
				        <?php echo EventStatusHelper::getSymbolByStatus($event->eventStatus); ?>
	                <?php endif; ?>
            </div>
            <div class="sdajem_event_box_category">
                <p>
                    <?php echo IconHelper::categoryIcon() ?>
                    <?php echo $event->category->title ?>
                </p>
            </div>
            <div class="sdajem_event_box_location">
                <p>
                    <?php echo IconHelper::locationIcon() ?>
                    <?php echo $event->location->title ?>
                </p>
            </div>
            <div class="sdajem_event_box_attendees">
                <p>
                    <?php echo IconHelper::usersIcon(); ?>
                    <?php
                    if ((bool) $event->useRegistration)
                    {
	                    echo $event->getAttendingCount();
                    }
                    ?>
                </p>
            </div>
        </div>

    </div>
</a>
<?php endforeach; ?>