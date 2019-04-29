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
$boxX = 40;
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
			<button id="toSvg"><?php echo Text::_('COM_SDAJEM_TO_SVG') ?></button>
			<button id="save"><?php echo Text::_('COM_SDAJEM_PLANING_SAVE') ?></button>
			<button id="toPng"><?php echo Text::_('COM_SDAJEM_PLANING_TO_PNG') ?></button>
		</div>

		<div id="messages">
			<button id="message_close" onclick="document.getElementById('messages').hidden = true;">
				<i class="fas fa-times" aria-hidden="true"></i>
			</button>
		</div>
	</div>

	<p>
		Element anklicken zum verschieben. Erneut klicken zum loslassen.
	</p>
	<p>
		Rote und grüne Punkte klicken zum Rotieren um 5°.
	</p>

	<?php
	// Set up draw area
	echo "<svg id=\"main_svg\"
	     xmlns=\"http://www.w3.org/2000/svg\"
         viewBox=\"0 0 $boxX $boxY\"
        onload=\"makeDraggable(evt)\"
	    >";

	echo "<rect x=\"0\" y=\"0\" width=\"$boxX\" height=\"$boxY\" fill=\"white\" />";

	for ($runX = 0; $runX <= $boxX; $runX++)
	{
		echo "<line x1=\"$runX\" y1=\"0\" x2=\"$runX\" y2=\"$boxY\" stroke='blue' stroke-width='0.1' stroke-opacity='0.1' />";
		echo "<line x1=\"$runX\" y1=\"0\" x2=\"$runX\" y2=\"1\" stroke='black' stroke-width='0.1' />";
		echo "<text x='" . ($runX + 0.2) ."' y=\"0.8\" style='font-size:0.4pt; fill:black'>" . ($runX+1) . "</text>";
	}

	for ($runY = 0; $runY <= $boxY; $runY++)
	{
		echo "<line x1=\"0\" y1=\"$runY\" x2=\"$boxX\" y2=\"$runY\" stroke='blue' stroke-width='0.1' stroke-opacity='0.1' />";
		echo "<line x1=\"0\" y1=\"$runY\" x2=\"1\" y2=\"$runY\" stroke='black' stroke-width='0.1' />";
		echo "<text x='0.2' y='" . ($runY+0.8) . "' style='font-size:0.4pt; fill:black'>" . ($runY+1) . "</text>";
	}

	// Do we have a saved setup
	if ($event->svg)
	{
		echo $event->svg;
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

					if ((bool) $fitting->typeModel->needSpace)
					{
						if ($by + $fitting->width > $boxY)
						{
							$by = 0;
							$bx = $wx + 1;
						}

						if (strpos($event->svg, 'img_' . $fitting->sdaprofiles_fitting_id) === false)
						{
							echo "<g class='draggme'>";

							if ($fitting->image->image)
							{
								echo "<image id='img_" . $fitting->sdaprofiles_fitting_id .
									"' xlink:href='" . $fitting->image->getDataURI() .
									"' name='fitting' class='draggable confine' x='" .
									$bx . "' y='" . $by .
									"' width='" . $fitting->length .
									"' height='" . $fitting->width . "'/>";
							}
							else
							{
								echo "<rect id='img_" . $fitting->sdaprofiles_fitting_id .
									"' name='fitting' class='draggable confine' x='" .
									$bx . "' y='" . $by .
									"' width='" . $fitting->length .
									"' height='" . $fitting->width . "' fill='green' fill-opacity='0.5' />";
							}
							echo "<text x='" .
								($fitting->length / 2 + $bx - 1) .
								"' y='" .
								($fitting->width + 1 + $by) .
								"' style='font-size:0.6pt;' opacity='0.0'>" . $attendee->user->username . "</text>";
							echo "<circle name='handle' class='rotate left handle' fill-opacity='0.0' fill='red' cx='" .
								($fitting->length / 2 + $bx - 1) . "' cy='" . ($fitting->width + 2 + $by) .
								"' r='0.5'/>";
							echo "<circle name='handle' class='rotate right handle' fill-opacity='0.0' fill='green' cx='" .
								($fitting->length / 2 + $bx + 1) . "' cy='" . ($fitting->width + 2 + $by) .
								"' r='0.5'/>";
							echo "</g>";
						}

						$wx = ($wx < $fitting->width) ? $fitting->width : $wx;
						$by = $by + $fitting->width + 2;
					}
				}
			}
		}
	}
	echo "</svg>";
	?>
	<input type="hidden" id="boxX" value="<?php echo $boxX; ?>" />
	<input type="hidden" id="boxY" value="<?php echo $boxY; ?>" />
	<input type="hidden" id="eventId" name="eventId" value="<?php echo $event->sdajem_event_id; ?>" />

</div>
