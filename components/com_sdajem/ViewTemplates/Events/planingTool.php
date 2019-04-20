<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;
use FOF30\Container\Container;

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
				<?php echo Text::_('COM_SDAJEM_TITLE_PLANINGTOOL_BASIC'); ?>
			</h2>
		</div>
		<div class="buttonsContainer">
		</div>
	</div>

	<svg xmlns="http://www.w3.org/2000/svg"
	     viewBox="0 0 50 50"
	     onload="makeDraggable(evt)">

		<?php
		if ($event->attendees)
		{
			$by = 0;

			// Store base x
			$bx = 0;

			// Store elements max width
			$wx = 0;
			/** @var \Sda\Jem\Admin\Model\Attendee $attendee */
			foreach ($event->attendees as $attendee)
			{
				if (count($attendee->sdaprofilesFittingIds) > 0)
				{
					foreach ($attendee->sdaprofilesFittingIds as $byd)
					{
						/** @var \Sda\Profiles\Admin\Model\Fitting $fitting */
						$fitting = Container::getInstance('com_sdaprofiles')->factory->model('Fitting');
						$fitting->load($byd);
						echo "<g>";
						echo "<image xlink:href='media/com_sdajem/images/alexb.png' name='fitting' class='draggable confine' x='" .
							$bx . "' y='" . $by .
							"' width='" . $fitting->length .
							"' height='" . $fitting->width . "'/>";
						echo "<circle name='handle' class='rotate' fill='red' cx='" .
							($fitting->length / 2 + $bx) . "' cy='" . ($fitting->width + 2 + $by) .
							"' r='0.5'/>";
						echo "</g>";

						$wx = ($wx < $fitting->width) ? $fitting->width : $wx;
						$by = $by + $fitting->width + 2;
						if ($by > 50)
						{
							$by = 0;
							$bx = $wx + 1;
						}
					}
				}
			}
		}
		?>




	</svg>
	<p>
		Hier sollte vielleicht eine Anleitung für den Umgang mit Excel Online hin !
	</p>

</div>
