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

use Yajem\User\YajemUserProfile;
use Joomla\CMS\Language\Text;
use Yajem\Models\EquipmentItem;

?>

<div id="yajem_container">
	<div class="yajem_section_container">
		<div class="yajem_profiles_grid">

			<?php foreach ($this->items as $item): ?>
			<?php
				// Not neccesary but very vonvenient for develeopment
				$profile = YajemUserProfile::cast($item);
			?>

			<div class="yajem_cell">
                <a href="<?php echo JRoute::_('index.php?option=com_yajem&task=profile.view&id=' . (int) $profile->id); ?>">
				    <img class="yajem_avatar yajem_img_round" src="<?php echo $profile->avatar; ?>" />
                </a>
			</div>
			<div class="yajem_cell">
                <a href="<?php echo JRoute::_('index.php?option=com_yajem&task=profile.view&id=' . (int) $profile->id); ?>">
				    <?php echo $profile->name ?> (<?php echo $profile->username ?>)
                </a>
			</div>
			<div class="yajem_cell">
				<?php echo Text::_('JGLOBAL_EMAIL') . ": " . $profile->email; ?> <br/>
				<?php echo Text::_('COM_YAJEM_PHONE') . ": " . $profile->phone; ?> <br/>
				<?php echo Text::_('COM_YAJEM_MOBIL') . ": " . $profile->mobil; ?>
			</div>
			<div class="yajem_cell">
				<?php echo $profile->address; ?> <br/>
				<?php echo $profile->plzCity; ?>
			</div>
			<div class="yajem_cell">
				<?php
				if ($profile->equipmentItems)
				{
					foreach ($profile->equipmentItems as $equipmentItem)
					{
						$item = EquipmentItem::cast($equipmentItem);
						echo $item->type . " " . $item->length . " x " . $item->width . "<br/>" . $item->detail . "<br/><br/>";
					}
				}
				?>
			</div>

	        <?php endforeach; ?>

        </div>
    </div>

</div>

