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

	<?php
	if (ComponentHelper::getParams('com_sdajem')->get('eventDefaultView')) {
		switch (ComponentHelper::getParams('com_sdajem')->get('eventDefaultView')) {
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

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>
