<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */
/** @var \Sda\Jem\Site\View\Attendee\Raw    $this        */
/** @var \Sda\Jem\Site\Model\Attendee       $attendee    */

use Joomla\CMS\Language\Text;

$attendee = $this->getModel('Attendee');

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

<?php if ($attendee->user->profile) : ?>

	<div class="sdajem_avatar_container">
		<img class="sdajem_avatar" src="<?php echo $attendee->user->profile->avatar; ?>" />
	</div>
	<div class="sdajem_profile_details">
		<?php echo $attendee->user->profile->userName . "<br/>" . $status; ?>
	</div>


<?php else : ?>

	<div class="sdajem_profile_details">
		<?php echo $attendee->user->username . "<br/>" . $status; ?>
	</div>

<?php endif; ?>

</div>
