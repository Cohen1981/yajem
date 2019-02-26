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

$profile = YajemUserProfile::cast($this->profile)

?>

<div id="yajem_container">

	<div class="yajem_container">
		<?php

		?>
	</div>

	<div class="yajem_profile_header">
		<img class="yajem_profile_avatar yajem_img_round" src="<?php echo $profile->avatar ?>"/>
		<h1><?php echo $profile->name ?></h1>
	</div>

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

</div>
