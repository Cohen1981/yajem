<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */
$event = $this->getModel('Event');
$this->addCssFile('media://com_sdajem/css/style.css');
// $this->addJavascriptFile('media://com_sdajem/js/jquery-3.3.1.min.js');
// $this->addJavascriptFile('media://com_sdajem/js/draw.js');

?>

<div id="planingTool">
	<div class="well">
		<div class="titleContainer">
			<h2 class="page-title">
				<?php echo Text::_('COM_SDAJEM_TITLE_PLANINGTOOL_BASIC'); ?>
			</h2>
		</div>
		<div class="buttonsContainer">
		</div>
	</div>

	<a href="https://eur01.safelinks.protection.outlook.com/ap/x-59584e83/?url=https%3A%2F%2F1drv.ms%2Fx%2Fs!As42z7o54BYWhl0slLVRNZzoO49N&data=02%7C01%7C%7C67108194ea3148f41ec108d6bd902c54%7C84df9e7fe9f640afb435aaaaaaaaaaaa%7C1%7C0%7C636904824242023490&sdata=lu8llTe9%2BtqoDbBrOlLGdxtDXDzMgVapzPZX%2BNqJldE%3D&reserved=0"
		target="_blank">
		<i class="fas fa-external-link-alt" aria-hidden="true"></i>
		<?php echo " " . Text::_('COM_SDAJEM_TITLE_PLANINGTOOL_BASIC'); ?>
	</a>

	<p></p>
	<p>
		Hier sollte vielleicht eine Anleitung für den Umgang mit Excel Online hin !
	</p>

</div>

