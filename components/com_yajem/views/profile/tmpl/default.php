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

	<div class="yajem_grid_section">

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

	<div class="yajem_grid_section">
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

</div>
