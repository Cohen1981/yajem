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
<?php if ($attendee->user->profile || $attendee->sdaprofiles_profile_id) : ?>

	<?php
	/** @var \Sda\Profiles\Site\Model\Profile $profile */
	if ($attendee->user->profile)
	{
		$profile = $attendee->user->profile;
	}
	else
	{
		$profile = Container::getInstance('com_sdaprofiles')->factory->model('Profile');
		$profile->load($attendee->sdaprofiles_profile_id);
	}
	?>

	<div class="sdajem_avatar_container">
		<img class="sdajem_avatar" src="<?php echo $profile->avatar; ?>" />
	</div>
	<div class="sdajem_profile_details">
		<?php echo $profile->userName . "<br/>" . $status; ?>
	</div>

<?php else : ?>

	<div class="sdajem_profile_details">
		<?php echo $attendee->user->username . "<br/>" . $status; ?>
	</div>

<?php endif; ?>

</div>
