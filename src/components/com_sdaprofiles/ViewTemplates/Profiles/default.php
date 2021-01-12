<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Factory;
use FOF30\Date\Date;
use Joomla\CMS\Language\Text;

/** @var \Sda\Profiles\Site\View\Profile\Html $this */
/** @var \Sda\Profiles\Site\Model\Profile $profile */

$this->addCssFile('media://com_sdaprofiles/css/sdaprofiles_style.css');
$items = $this->getItems();
?>

<form action="<?php echo JRoute::_('index.php?option=com_sdaprofiles&view=Profiles'); ?>" method="post"
      name="adminForm" id="adminForm">

	<div id="sdaprofiles_container">

		<?php foreach ($this->items as $profile) : ?>

		<div class="sdaprofiles_section_container">
            <?php if ($profile->user->id == Factory::getUser()->id) : ?>
            <a href="<?php echo JRoute::_('index.php?option=com_sdaprofiles&task=edit&id=' . (int) $profile->sdaprofiles_profile_id); ?>">
            <?php else: ?>
            <a href="<?php echo JRoute::_('index.php?option=com_sdaprofiles&task=read&id=' . (int) $profile->sdaprofiles_profile_id); ?>">
            <?php endif; ?>
			<div class="sdaprofiles_flex_row">

				<div id="avatar_cell" class="sdaprofiles_cell">
					<?php
					if ($profile->avatar)
					{
						echo "<img class=\"sdaprofiles_avatar\" src=\"" . $profile->avatar . "\"/>";
					}
					else
					{
						echo "<img class=\"sdaprofiles_avatar\" src=\"media/com_sdaprofiles/images/user-image-blanco.png\"/>";
					}
					?>

                    <?php echo $profile->userName; ?>

					<?php if ((bool) $profile->defaultGroup) : ?>
						<i id="defaultMarker"
						   class="fas fa-star"
						   aria-hidden="true"
						   title="<?php echo Text::_('COM_SDAPROFILES_PROFILE_DEFAULTGROUP_LABEL') ?>">
						</i>
					<?php endif; ?>
				</div>
                <div class="sda_cell_container">
                    <div class="sdaprofiles_cell">
                        <?php  if (!(bool) $profile->groupProfile) : ?>
                        <i class="fas fa-home" aria-hidden="true"></i>
                        <div class="sdaprofile_detail">
                        <?php
                            if ($profile->address1) { echo $profile->address1 . "<br/>"; }
                            if ($profile->address2) { echo $profile->address2 . "<br/>"; }
                            if ($profile->city || $profile->postal) {
	                            echo $profile->postal . " " . $profile->city;
                            }
                        ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="sdaprofiles_cell">
                        <?php  if (!(bool) $profile->groupProfile) : ?>
                        <i class="fas fa-mobile-alt" aria-hidden="true"></i>
                        <div class="sdaprofile_detail">
                        <?php
                            if ($profile->phone) { echo $profile->phone . "<br/>"; }
                            if ($profile->mobil) { echo $profile->mobil; }
                        ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="sdaprofiles_cell">
                        <?php if (!(bool) $profile->groupProfile) : ?>
                        <i class="fas fa-birthday-cake" aria-hidden="true"></i>
                        <div class="sdaprofile_detail">
                        <?php
                            $date = new Date($profile->dob);
                            echo $date->format('d.m.Y');
                        ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
			</div>
            </a>
		</div>

		<?php endforeach; ?>
	</div>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>

