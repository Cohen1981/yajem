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

	<form name="fittingForm" id="fittingForm" class="form-horizontal">
		<div class="well">
			<div class="titleContainer">
				<h2 class="page-title">
					<span class="icon-generic" aria-hidden="true"></span>
					<?php echo Text::_('COM_SDAProfiles_TITLE_FITTINGS_BASIC'); ?>
				</h2>
			</div>
			<div class="buttonsContainer">
				<button id="sdaprofiles_fitting_add" type="button" onclick="addFittingAjax()">
					<?php echo Text::_('SDAPROFILES_FITTING_NEW') ?>
				</button>
			</div>
		</div>

		<div class="control-group ">
			<label class="control-label " for="sdaprofiles_input_type">
				<?php echo Text::_('COM_SDAPROFILES_FITTING_TYPE_LABEL'); ?>
			</label>
			<div class="controls">
				<input id="sdaprofiles_input_type" type="text" name="type" value="" />
				<span class="help-block">
					<?php echo Text::_('COM_SDAPROFILES_FITTING_TYPE_DESC'); ?>
				</span>
			</div>
		</div>

		<div class="control-group ">
			<label class="control-label " for="sdaprofiles_input_type">
				<?php echo Text::_('COM_SDAPROFILES_FITTING_DETAIL_LABEL'); ?>
			</label>
			<div class="controls">
				<input id="sdaprofiles_input_detail" type="text" name="detail" value="" />
				<span class="help-block">
					<?php echo Text::_('COM_SDAPROFILES_FITTING_DETAIL_DESC'); ?>
				</span>
			</div>
		</div>

		<div class="control-group ">
			<label class="control-label " for="sdaprofiles_input_type">
				<?php echo Text::_('COM_SDAPROFILES_FITTING_LENGHT_LABEL'); ?>
			</label>
			<div class="controls">
				<input id="sdaprofiles_input_length" type="number" name="length" value="" />
				<span class="help-block">
					<?php echo Text::_('COM_SDAPROFILES_FITTING_LENGHT_DESC'); ?>
				</span>
			</div>
		</div>

		<div class="control-group ">
			<label class="control-label " for="sdaprofiles_input_type">
				<?php echo Text::_('COM_SDAPROFILES_FITTING_WIDTH_LABEL'); ?>
			</label>
			<div class="controls">
				<input id="sdaprofiles_input_width" type="number" name="width" value="" />
				<span class="help-block">
					<?php echo Text::_('COM_SDAPROFILES_FITTING_WIDTH_DESC'); ?>
				</span>
			</div>
		</div>

		<input type="hidden" name="profileId" value="<?php echo $profile->sdaprofiles_profile_id; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>

	<div class="well">
		<div class="titleContainer">
			<h2 class="page-title">
				<span class="icon-generic" aria-hidden="true"></span>
				<?php echo Text::_('COM_SDAPROFILES_TITLE_FITTINGS_STOCK'); ?>
			</h2>
		</div>
	</div>
	<div id="fitting_area" class="form-horizontal">
		<div id="sdaprofiles_fitting" class="control-group">
			<label class="control-label">
			</label>
			<div class="controls">
				<span class="sdaprofiles_fitting_cell"><?php echo Text::_('COM_SDAPROFILES_FITTING_TYPE_LABEL'); ?></span>
				<span class="sdaprofiles_fitting_cell"><?php echo Text::_('COM_SDAPROFILES_FITTING_DETAIL_LABEL'); ?></span>
				<span class="sdaprofiles_fitting_cell"><?php echo Text::_('COM_SDAPROFILES_FITTING_LENGHT_LABEL'); ?></span>
				<span class="sdaprofiles_fitting_cell"><?php echo Text::_('COM_SDAPROFILES_FITTING_WIDTH_LABEL'); ?></span>
			</div>
		</div>
		<?php if ($profile->fittings) : ?>
		<?php foreach ($profile->fittings as $fitting) : ?>
			<div id="sdaprofiles_fitting_<?php echo $fitting->sdaprofiles_fitting_id; ?>" class="control-group">
				<label class="control-label">
					<button type="button" onclick="deleteFittingAjax(<?php echo $fitting->sdaprofiles_fitting_id; ?>)">
						<i class="fas fa-trash" aria-hidden="true" title="<?php echo JText::_('COM_SDAPROFILES_ICON_DELETE') ?>"></i>
					</button>
				</label>
				<div class="controls">
					<span class="sdaprofiles_fitting_cell"><?php echo $fitting->type ?></span>
					<span class="sdaprofiles_fitting_cell"><?php echo $fitting->detail ?></span>
					<span class="sdaprofiles_fitting_cell"><?php echo $fitting->length ?></span>
					<span class="sdaprofiles_fitting_cell"><?php echo $fitting->width ?></span>
				</div>
			</div>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<?php
	if ($profile->attendees)
	{
		echo $this->loadAnyTemplate('site:com_sdajem/Attendee/attendings');
	}
	?>

</div>
