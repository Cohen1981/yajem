<?php
/**
 * @package     Yajem.Site
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Yajem\User\YajemUserProfile;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Yajem\Models\EquipmentItem;

$profile = YajemUserProfile::cast($this->profile);

$user = Factory::getUser();
$userId = $user->get('id');

?>

<div id="yajem_container">

	<div class="yajem_profile_header">
		<img class="yajem_profile_avatar yajem_img_round" src="<?php echo $profile->avatar ?>"/>
		<div class="yajem_container">
			<h1><?php echo $profile->name ?></h1>
			<?php if($userId == $profile->id): ?>
				<a href="<?php echo Route::_('index.php/user?task=profile.edit&user_id=' . $userId); ?>">
					<?php echo Text::_('COM_YAJEM_EDIT'); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>

	<h2><?php echo Text::_('COM_YAJEM_CONTACT_DETAIL'); ?></h2>

	<div class="yajem_grid_section yajem_bordered yajem_padding">

		<div>
			<?php echo Text::_('COM_YAJEM_NAME'); ?>
		</div>
		<div>
			<?php echo $profile->name; ?>
		</div>

		<div>
			<?php echo Text::_('COM_YAJEM_MAIL'); ?>
		</div>
		<div>
			<?php echo $profile->email; ?>
		</div>

		<div>
			<?php echo Text::_('COM_YAJEM_PHONE'); ?>
		</div>
		<div>
			<?php echo $profile->phone; ?>
		</div>

		<div>
			<?php echo Text::_('COM_YAJEM_MOBIL'); ?>
		</div>
		<div>
			<?php echo $profile->mobil; ?>
		</div>

		<div>
			<?php echo Text::_('COM_YAJEM_ADDRESS'); ?>
		</div>
		<div>
			<?php echo $profile->address; ?>
		</div>

		<div>
			<?php echo Text::_('COM_YAJEM_PLZCITY'); ?>
		</div>
		<div>
			<?php echo $profile->plzCity; ?>
		</div>

		<div>
			<?php echo Text::_('COM_YAJEM_DOB'); ?>
		</div>
		<div>
			<?php echo $profile->getFormatedBirthDate(); ?>
		</div>

	</div>

	<h2><?php echo Text::_('COM_YAJEM_EQUIPMENT'); ?></h2>

	<div class="yajem_grid_section yajem_bordered yajem_padding">
		<div>
			<b><?php echo Text::_('COM_YAJEM_EQUIPMENT_DESC'); ?></b>
		</div>
		<div>
			<b><?php echo Text::_('COM_YAJEM_EQUIPMENT_AREA'); ?></b>
		</div>
		<?php if($profile->equipmentItems): ?>
			<?php foreach ($profile->equipmentItems as $item) : ?>
			<?php $item = EquipmentItem::cast($item); ?>
				<div>
					<?php echo $item->type; ?><br/>
					<?php echo $item->detail; ?>
				</div>
				<div>
					<?php echo $item->length . " x " . $item->width; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<h2><?php echo Text::_('COM_YAJEM_ATTENDET'); ?></h2>

	<div class="yajem_grid_section yajem_bordered yajem_padding">
		<?php
		if ($attendants = $profile->getAttendants())
		{
			foreach ($attendants as $attendet)
			{
				switch ($attendet->status)
				{
					case 0:
						$userStatus = '<div> <i class="fas fa-question-circle" aria-hidden="true"> </i> ' .
							Text::_("COM_YAJEM_NOT_DECIDED") . '</div>';
						break;
					case 1:
						$userStatus = '<div> <i class="far fa-thumbs-up" aria-hidden="true"> </i> ' .
							Text::_("COM_YAJEM_ATTENDING") . '</div>';
						break;
					case 2:
						$userStatus = '<div> <i class="far fa-thumbs-down" aria-hidden="true"> </i> ' .
							Text::_("COM_YAJEM_NOT_ATTENDING") . '</div>';
						break;
					case 3:
						$userStatus = '<div> <i class="far fa-clock" aria-hidden="true"> </i> ' .
							Text::_("COM_YAJEM_ON_WAITINGLIST") . '</div>';
						break;
				}

				echo "<div><a href=\"" .
					JRoute::_('index.php?option=com_yajem&task=event.view&id=' . (int) $attendet->eventId) . "\">" .
					$attendet->event . "</a></div>";
				echo $userStatus;
			}
		}
		?>
	</div>

</div>
