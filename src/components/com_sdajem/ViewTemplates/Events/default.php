<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use FOF30\Container\Container;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use FOF30\Date\Date;
use Joomla\CMS\Language\Text;
use Sda\Html\Helper;
use Sda\Jem\Admin\Helper\EventStatusHelper;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */

$this->addCssFile('media://com_sdajem/css/sdajem_style.css');
$this->addJavascriptFile('media://jui/js/jquery.min.js');
$this->addJavascriptFile('media://com_sda/js/filter.js');
$currentDate = new Date;

/** @var \Sda\Jem\Site\Model\Event $model */
$model = $this->getModel();

/** @var \Sda\Jem\Admin\Model\Event $eventModel */
$eventModel = Container::getInstance('com_sdajem')->factory->model('Event');
/** @var \Sda\Jem\Admin\Model\Location $locationModel */
$locationModel = Container::getInstance('com_sdajem')->factory->model('Location');

$guest = Factory::getUser()->guest;

$viewParam = Factory::getApplication()->getUserState('com_sdajem.eventsView', ComponentHelper::getParams('com_sdajem')->get('eventDefaultView'));

$filterDate = ComponentHelper::getParams('com_sdajem')->get('filterDate');
$filterEventStatus = ComponentHelper::getParams('com_sdajem')->get('filterEventStatus');
$filterLocation = ComponentHelper::getParams('com_sdajem')->get('filterLocation');

$activeFilters = ($filterDate || $filterEventStatus || $filterLocation) ? true:false;

?>
<form action="<?php echo JRoute::_('index.php?option=com_sdajem&view=Events'); ?>" method="post"
      name="adminForm" id="adminForm">

    <div id="sdajem_view_control">
        <div class="sdajem_controls">
            <p> <?php echo Text::_('COM_SDAJEM_VIEW_CONTROL'); ?></p>

            <button
                    formmethod="post"
                    onclick="document.getElementById('task').value='eventsFlexView'"
                    formaction="<?php echo JRoute::_('index.php?option=com_sdajem&view=Events'); ?>"
                    class="sdajem_view_button"
            >
                <i class="fas fa-bars" aria-hidden="true"> </i>
            </button>
            <button
                    formmethod="post"
                    onclick="document.getElementById('task').value='eventsBoxedView'"
                    formaction="<?php echo JRoute::_('index.php?option=com_sdajem&view=Events'); ?>"
                    class="sdajem_view_button"
            >
                <i class="fas fa-th-list" aria-hidden="true"></i>
            </button>
        </div>
        <?php if ($activeFilters) :?>
        <div class="sda_sub_flexed">
	        <?php if ($filterDate) :?>
            <div class="sdajem_controls sda_flexed">
                <?php
                $filters = $eventModel->getFilters('startDateTime');
                echo Helper::getFilter('COM_SDAJEM_EVENT_MONTH', $filters);
                ?>
            </div>
	        <?php endif; ?>
	        <?php if ($filterEventStatus) :?>
            <div class="sdajem_controls sda_flexed">
                <?php
                $filters = $eventModel->getFilters('eventStatus');
                foreach ($filters as &$filter) {
                    $filter = EventStatusHelper::getStatusTextByStatus((int) $filter);
                }
                echo Helper::getFilter('COM_SDAJEM_EVENT_STATUS', $filters);
                ?>
            </div>
	        <?php endif; ?>
	        <?php if ($filterLocation) :?>
            <div class="sdajem_controls sda_flexed">
                <?php
                $filters = $locationModel->getFilters('title');
                echo Helper::getFilter('COM_SDAJEM_LOCATION', $filters);
                ?>
            </div>
	        <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

	<?php
	if ($viewParam) {
		switch ($viewParam) {
			case 0:
				echo $this->loadAnyTemplate('site:com_sdajem/Events/default_flex');
				break;
			case 1:
				echo $this->loadAnyTemplate('site:com_sdajem/Events/default_boxed');
				break;
		}
	}
	else
	{
		echo $this->loadAnyTemplate('site:com_sdajem/Events/default_flex');
    }

	?>

	<input type="hidden" id="task" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>
