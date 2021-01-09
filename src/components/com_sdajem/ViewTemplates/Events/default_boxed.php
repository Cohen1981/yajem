<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use FOF30\Date\Date;
use Joomla\CMS\Language\Text;
use Sda\Jem\Admin\Helper\EventStatusHelper;
use Sda\Jem\Admin\Helper\IconHelper;
use Joomla\CMS\Router\Route;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */

$this->addCssFile('media://com_sdajem/css/sdajem_style.css');
$currentDate = new Date;

/** @var \Sda\Jem\Site\Model\Event $model */
$model = $this->getModel();

// Show only enabled events sorted ascending
$items = $model->where('enabled', '=', 1)
	->orderBy('startDateTime')->get();

$guest = Factory::getUser()->guest;
$lastMonth = '';
$viewParam = Factory::getApplication()->getUserState('com_sdajem.eventsView', ComponentHelper::getParams('com_sdajem')->get('guestDefaultEventView'));
?>

<?php foreach ($items as $event): ?>
<?php
    if(($viewParam == 1 && $guest && $viewParam == $event->eventStatus) || $viewParam == 0 || !$guest) {
	    $event->applyAccessFiltering();
	    $currentMonth = str_replace(' ', '_', $event->getStartMonth());

	    $class = '';
	    $class = $class . ' ' . str_replace(' ', '_', $event->location->title);
	    $class = $class . ' ' . str_replace(' ', '_', EventStatusHelper::getStatusTextByStatus($event->eventStatus));
	    $class = $class . ' ' . $currentMonth;
	    $upcoming = ($event->startDateTime > $currentDate) ? 'upcoming' : 'past';
	    $class = $class . ' ' . $upcoming;

	    if ($currentMonth <> $lastMonth) {
		    echo "<div class='sda_month_divider filterRow " . $currentMonth . " " . $upcoming . "'>";
		    echo "<h1>$currentMonth</h1>";
		    echo "</div>";
		    $lastMonth = $currentMonth;
	    }
    }
?>

<?php if(($viewParam == 1 && $guest && $viewParam == $event->eventStatus) || $viewParam == 0 || !$guest): ?>

<a class="no_decoration filterRow <?php echo $class;?>"
   href="<?php echo Route::_('index.php?option=com_sdajem&task=read&id=' . $event->sdajem_event_id) ?>">
    <div class="sdajem_event_box filterRow <?php echo $class;?>">

        <div class="sdajem_image_container flex_col">
            <div class="sdajem_event_box_date">
                <p>
			        <?php echo IconHelper::dateIcon() ?>
			        <?php echo $event->getFormatedStartDate() ?>
                    <i class="fas fa-angle-double-right" aria-hidden="true"></i>
			        <?php echo $event->getFormatedEndDate() ?>
                </p>
            </div>

            <?php if ($event->image) :?>
	            <img class='sdajem_round_img' src="<?php echo $event->image; ?>" alt="<?php echo Text::_('COM_SDAJEM_EVET_IMAGE'); ?>" />
            <?php endif; ?>
        </div>

        <div class="sdajem_event_details flex_col">
	        <?php
	        if ((bool)$event->eventCancelled) {
		        echo \Sda\Html\Helper::getEventCanceledByHostSymbol();
	        }
	        ?>
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
                    <?php
                    if ((bool) $event->useRegistration) {
	                    echo IconHelper::usersIcon();
	                    echo $event->getAttendingCount();
                    }

                    if (!$guest) {
                        echo "<span class=\"sdajem_spacer1\"></span>";

                        if($event->comments->count() > 0) {
                            $comCount = $event->hasUnreadComments(Factory::getUser()->id);
                            if ($comCount > 0 ) {
                                $class = 'sdajem_text_red';
                                $title = $comCount . " " . Text::_("COM_SDAJEM_NEW_COMMENTS");
                            }
                            echo "<span class=\"$class\" title=\"$title\">";
                            echo IconHelper::commentsIcon() . $event->comments->count();
                            echo "</span>";
                        }
                    }
                    ?>
                </p>
            </div>

            <?php if (!$guest) :?>
            <div class="sdajem_attending_status">
                <p>
                    <?php echo EventStatusHelper::getStatusLabel(Factory::getUser()->id, $event->sdajem_event_id) ;?>
                </p>
            </div>
            <?php endif; ?>
        </div>

    </div>
</a>

<?php endif; ?>
<?php endforeach; ?>