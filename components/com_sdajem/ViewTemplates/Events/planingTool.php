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
$this->addJavascriptFile('media://com_sdajem/js/jquery-3.3.1.min.js');
$this->addJavascriptFile('media://com_sdajem/js/draw.js');
$boxX = 30;
$boxY = 30;

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

	<p>
		Rote und grüne Punkte klicken zum Rotieren um 5°.
	</p>

	<svg xmlns="http://www.w3.org/2000/svg"
	     viewBox="0 0 <?php echo $boxX . " " . $boxY; ?>"
	     onload="makeDraggable(evt)">

		<?php
		// Set up draw area
		for ($runX = 0; $runX <= $boxX; $runX++)
		{
			echo "<line class='background' x1=\"$runX\" y1=\"0\" x2=\"$runX\" y2=\"$boxY\" />";
			echo "<line x1=\"$runX\" y1=\"0\" x2=\"$runX\" y2=\"1\" stroke='black' stroke-width='0.1' />";
			echo "<text x='" . ($runX + 0.2) ."' y=\"0.8\" style='font-size:0.4pt;'>" . ($runX+1) . "</text>";
		}

		for ($runY = 0; $runY <= $boxY; $runY++)
		{
			echo "<line class='background' x1=\"0\" y1=\"$runY\" x2=\"$boxX\" y2=\"$runY\" />";
			echo "<line x1=\"0\" y1=\"$runY\" x2=\"1\" y2=\"$runY\" stroke='black' stroke-width='0.1' />";
			echo "<text x='0.2' y='" . ($runY+0.8) . "' style='font-size:0.4pt;'>" . ($runY+1) . "</text>";
		}

		// Do we have Attendees
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
				// Attendee has checked Equipment
				if (count($attendee->sdaprofilesFittingIds) > 0)
				{
					foreach ($attendee->sdaprofilesFittingIds as $fIds)
					{
						/** @var \Sda\Profiles\Admin\Model\Fitting $fitting */
						$fitting = Container::getInstance('com_sdaprofiles')->factory->model('Fitting');
						$fitting->load($fIds);

						if ($fitting->type != 3)
						{
							echo "<g>";
							if ($fitting->image)
							{
								echo "<image xlink:href='" . $fitting->image . "' name='fitting' class='draggable confine' x='" .
									$bx . "' y='" . $by .
									"' width='" . $fitting->length .
									"' height='" . $fitting->width . "'/>";
							}
							else
							{
								echo "<rect name='fitting' class='draggable confine' x='" .
									$bx . "' y='" . $by .
									"' width='" . $fitting->length .
									"' height='" . $fitting->width . "'/>";
							}
							echo "<text x='" .
								($fitting->length / 2 + $bx - 1 ) .
								"' y='" .
								($fitting->width + 1 + $by) .
								"' style='font-size:0.6pt;'>" . $attendee->user->username . "</text>";
							echo "<circle name='handle' class='rotate left handle' fill='red' cx='" .
								($fitting->length / 2 + $bx - 1) . "' cy='" . ($fitting->width + 2 + $by) .
								"' r='0.5'/>";
							echo "<circle name='handle' class='rotate right handle' fill='green' cx='" .
								($fitting->length / 2 + $bx + 1) . "' cy='" . ($fitting->width + 2 + $by) .
								"' r='0.5'/>";
							echo "</g>";

							$wx = ($wx < $fitting->width) ? $fitting->width : $wx;
							$by = $by + $fitting->width + 2;
							if ($by > $boxY)
							{
								$by = 0;
								$bx = $wx + 1;
							}
						}
					}
				}
			}
		}
		?>
	</svg>
	<input type="hidden" id="boxX" value="<?php echo $boxX; ?>" />
	<input type="hidden" id="boxY" value="<?php echo $boxY; ?>" />

</div>
