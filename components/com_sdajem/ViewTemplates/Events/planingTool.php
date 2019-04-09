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
$this->addJavascriptFile('media://com_sdajem/js/draw.js');

?>

<div id="planingTool">
	<div class="well">
		<div class="titleContainer">
			<h2 class="page-title">
				<span class="icon-comments-2" aria-hidden="true"></span>
				<?php echo Text::_('COM_SDAJEM_TITLE_PLANINGTOOL_BASIC'); ?>
			</h2>
		</div>
		<div class="buttonsContainer">
		</div>
	</div>

	<svg id="drawingArea" width="100%" height="100%" viewBox="0 0 40 40" onload="svg()">

		<g id="base" class="draggable">
			<rect width="9" height="7" rx="1" ry="1" fill="green" />
			<circle class="knob" r="1" cy="6" cx="8" fill="#7CFC00" />
		</g>

	</svg>

</div>

