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
use Joomla\CMS\Component\ComponentHelper;

/** @var \Sda\Jem\Site\View\Attendee\Raw    $this        */
/** @var \Sda\Jem\Site\Model\Attendee       $attendee    */
/** @var \Sda\Profiles\Site\Model\Fitting   $fitting     */

$attendee = $this->getModel('Attendee');

if (ComponentHelper::isEnabled('com_sdaprofiles'))
{
	$fitting = Container::getInstance('com_sdaprofiles')->factory->model('Fitting');
}

switch ($attendee->status)
{
	case 0:
		$status = "<div class=\"sdajem_status_label sdajem_grey\">" . Text::_('COM_SDAJEM_UNDECIDED') . "</div>";
		break;
	case 1:
		$status = "<div class=\"sdajem_status_label sdajem_green\">" . Text::_('COM_SDAJEM_ATTENDING') . "</div>";
		break;
	case 2:
		$status = "<div class=\"sdajem_status_label sdajem_red\">" . Text::_('COM_SDAJEM_NATTENDING') . "</div>";
		break;
}
?>

<div id="attendee<?php echo $attendee->sdajem_attendee_id; ?>" class="sdajem_profile_container">

<!-- Are we using profiles -->
<?php if ($attendee->user->profile) : ?>

	<div class="sdajem_avatar_container">
		<img class="sdajem_avatar" src="<?php echo $attendee->user->profile->avatar; ?>" />
	</div>
	<div class="sdajem_profile_details">
		<?php echo $attendee->user->profile->userName . "<br/>" . $status; ?>
	</div>

	<?php
	// Has the user checked equipment
	if ($attendee->sdaprofilesFittingIds)
	{
		$requiredSpace = 0;

		echo "<input type=\"checkbox\" id=\"sdajem_switch_fitting" . $attendee->sdajem_attendee_id . "\" class=\"sdajem_hidden\" hidden/>";
		echo "<div class=\"sdajem_section_container sdajem_switchable\">";

		foreach ($attendee->sdaprofilesFittingIds as $id)
		{
			$fitting->load($id);
			$requiredSpace = $requiredSpace + $fitting->getRequiredSpace();
			echo "<div class='equipment'>" . $fitting->type . " " . $fitting->length . "x" . $fitting->width . "</div>";
		}

		echo "</div>";
		echo "<label id=\"fitting-section-button\" class=\"sdajem_switch\" for=\"sdajem_switch_fitting" . $attendee->sdajem_attendee_id . "\">";
		echo Text::_('COM_SDAJEM_REQUIRED_SPACE') . ": " . $requiredSpace;
		echo "</label>";
	}
	?>

<?php else : ?>

	<div class="sdajem_profile_details">
		<?php echo $attendee->user->username . "<br/>" . $status; ?>
	</div>

<?php endif; ?>

</div>