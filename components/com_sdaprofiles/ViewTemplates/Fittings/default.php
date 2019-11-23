<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;

/** @var \Sda\Profiles\Site\View\Profile\Html $this */
/** @var \Sda\Profiles\Site\Model\Fitting $fitting */
/** @var \Sda\Profiles\Site\Model\FittingType $FType */
/** @var \Sda\Profiles\Site\Model\Profile $profile */

$this->addCssFile('media://com_sdaprofiles/css/style.css');
$this->addJavascriptFile('media://jui/js/jquery.min.js');
$this->addJavascriptFile('media://com_sdaprofiles/js/filter.js');

$items = $this->getItems();
$fTypeModel = $this->getContainer()->factory->model('FittingType');
$fTypes = $fTypeModel->getItemsArray();
$ProfileModel = $this->getContainer()->factory->model('Profile');
$profiles = $ProfileModel->getItemsArray();
?>

<form action="<?php echo JRoute::_('index.php?option=com_sdaprofiles&view=Fittings'); ?>" method="post"
      name="adminForm" id="adminForm">

	<div id="sdaprofiles_container">

		<!-- Filter -->
		<div class="sdaprofiles_cell">
			<label for="fiter_owner"><?php echo Text::_("COM_SDAPROFILES_FITTINGS_OWNER"); ?></label>
			<select name="filter_owner" id="filter_owner" onchange="filter('filter_owner')">
				<option value="all"><?php echo Text::_("COM_SDAPROFILES_FITTINGS_FILTER_ALL"); ?></option>
				<?php
				foreach ($profiles as $profile)
				{
					echo "<option>" . $profile->userName . "</option>";
				}
				?>
			</select>
		</div>
		<div class="sdaprofiles_cell">
			<label for="filter_type"><?php echo Text::_("COM_SDAPROFILES_FITTINGTYPE_TITLE_LABEL"); ?></label>
			<select id="filter_type" name="filter_type" onchange="filter('filter_type')">
				<option value="all"><?php echo Text::_("COM_SDAPROFILES_FITTINGS_FILTER_ALL"); ?></option>
				<?php

				foreach ($fTypes as $FType)
				{
					echo "<option>" . $FType->title . "</option>";
				}

				?>
			</select>
		</div>

		<div class="sdaprofiles_section_container">

			<!-- table header -->
			<div class="sdaprofiles_flex_row">
				<div class="sdaprofiles_cell sdaprofiles_header"><?php echo Text::_("COM_SDAPROFILES_FITTINGS_OWNER"); ?></div>
				<div class="sdaprofiles_cell sdaprofiles_header"><?php echo Text::_("COM_SDAPROFILES_FITTINGTYPE_TITLE_LABEL"); ?></div>
				<div class="sdaprofiles_cell sdaprofiles_header"><?php echo Text::_("COM_SDAPROFILES_FITTINGIMAGE_DESC_LABEL"); ?></div>
				<div class="sdaprofiles_cell sdaprofiles_header"><?php echo Text::_("COM_SDAPROFILES_FITTINGS_SIZE"); ?></div>
			</div>

			<?php foreach ($this->items as $fitting) : ?>

			<div class="sdaprofiles_flex_row filterRow <?php echo $fitting->profile->userName . " " . $fitting->typeModel->title;?>">

				<div id="avatar_cell" class="sdaprofiles_cell">
					<?php
					if ($fitting->profile->avatar)
					{
						echo "<img class=\"sdaprofiles_avatar\" src=\"" . $fitting->profile->avatar . "\"/>";
					}
					else
					{
						echo "<img class=\"sdaprofiles_avatar\" src=\"media/com_sdaprofiles/images/user-image-blanco.png\"/>";
					}
					?>

					<?php echo $fitting->profile->userName; ?>

				</div>
				<div class="sdaprofiles_cell">
					<?php echo $fitting->typeModel->title; ?>
				</div>
				<div class="sdaprofiles_cell">
					<?php echo $fitting->detail; ?>
				</div>
				<div class="sdaprofiles_cell">
					<?php echo $fitting->width . " x " . $fitting->length; ?>
				</div>

			</div>
			<?php endforeach; ?>
		</div>
	</div>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>


