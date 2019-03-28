<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;

/** @var \Sda\Profiles\Site\View\Profile\Html   $this       */
/** @var \Sda\Profiles\Site\Model\Profile       $profile    */
/** @var \Sda\Profiles\Site\Model\Fitting       $fitting    */

$this->addCssFile('media://com_sdaprofiles/css/style.css');
$this->addJavascriptFile('media://com_sdaprofiles/js/profile.js');
$profile = $this->getItem();
echo $this->getRenderedForm();

?>

<div class="sdaprofiles_fitting_container">

	<form name="fittingForm" id="fittingForm">
		<div class="well">
			<div class="titleContainer">
				<h2 class="page-title">
					<span class="icon-generic" aria-hidden="true"></span>
					<?php echo Text::_('COM_SDAProfiles_TITLE_FITTINGS_BASIC'); ?>
				</h2>
			</div>
			<div class="buttonsContainer">
				<button type="button" onclick="addFitting()"><?php echo Text::_('SDAPROFILES_FITTING_NEW') ?></button>
			</div>
		</div>

		<input type="text" name="type" value="" />
		<input type="text" name="detail" value="" />
		<input type="number" name="length" value="" />
		<input type="number" name="width" value="" />

		<input type="hidden" name="profileId" value="<?php echo $profile->sdaprofiles_profile_id; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>

	<div id="fitting_area">
		<?php if ($profile->fittings) : ?>
		<?php foreach ($profile->fittings as $fitting) : ?>
			<div id="sdaprofiles_fitting_<?php echo $fitting->sdaprofiles_fitting_id; ?>" class="sdaprofiles_flex_row">
				<div class="sdaprofiles_cell"><?php echo $fitting->type ?></div>
				<div class="sdaprofiles_cell"><?php echo $fitting->detail ?></div>
				<div class="sdaprofiles_cell"><?php echo $fitting->length ?></div>
				<div class="sdaprofiles_cell"><?php echo $fitting->width ?></div>
			</div>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>

</div>
