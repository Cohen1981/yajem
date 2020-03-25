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

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */

$this->addCssFile('media://com_sdajem/css/sdajem_style.css');
$currentDate = new Date;

/** @var \Sda\Jem\Site\Model\Event $model */
$model = $this->getModel();

// Show only future events sorted ascending
$items = $model->where('startDateTime', '>=', $currentDate->toSql())
	->where('enabled', '=', 1)
	->orderBy('startDateTime')->get();

$guest = Factory::getUser()->guest;

$viewParam = Factory::getApplication()->getUserState('com_sdajem.eventsView', ComponentHelper::getParams('com_sdajem')->get('eventDefaultView'))

?>
<form action="<?php echo JRoute::_('index.php?option=com_sdajem&view=Events'); ?>" method="post"
      name="adminForm" id="adminForm">

    <div id="sdajem_view_control">

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
