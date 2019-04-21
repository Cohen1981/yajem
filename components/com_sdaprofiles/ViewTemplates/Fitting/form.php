<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;

/** @var FOF30\View\DataView\Raw $this */

$this->addCssFile('media://com_sdaprofiles/css/style.css');
$this->addJavascriptFile('media://com_sdaprofiles/js/fittings.js');
$input = $this->input->getArray();
?>

<form name="fittingForm" id="fittingForm" class="form-horizontal" hidden>
	<div class="well">
		<div class="titleContainer">
			<h2 class="page-title">
				<span class="icon-generic" aria-hidden="true"></span>
				<?php echo Text::_('COM_SDAPROFILES_TITLE_FITTINGS_BASIC'); ?>
			</h2>
		</div>
		<div class="buttonsContainer">
			<button id="sdaprofiles_fitting_add" type="button" onclick="addFittingAjax()">
				<?php echo Text::_('SDAPROFILES_FITTING_SAVE') ?>
			</button>
		</div>
	</div>

	<div id="fitting-form-block">
	<div class="control-group ">
		<label class="control-label " for="sdaprofiles_input_type">
			<?php echo Text::_('COM_SDAPROFILES_FITTING_TYPE_LABEL') . "*"; ?>
		</label>
		<div class="controls">
			<select id="sdaprofiles_input_type" name="type" required>
				<option value="0" selected><?php echo Text::_('COM_SDAPROFIES_FITTING_TYPE_SELECT'); ?></option>
				<option value="1"><?php echo Text::_('COM_SDAPROFIES_FITTING_TYPE_TENT'); ?></option>
				<option value="2"><?php echo Text::_('COM_SDAPROFIES_FITTING_TYPE_SUNSAIL'); ?></option>
				<option value="3"><?php echo Text::_('COM_SDAPROFIES_FITTING_TYPE_MISC'); ?></option>
			</select>
			<span class="help-block">
				<?php echo Text::_('COM_SDAPROFILES_FITTING_TYPE_DESC'); ?>
			</span>
		</div>
	</div>

	<!-- TODO Warum wird der Text nicht übersetzt -->
	<input type="hidden"
	       id="sdaprofilesTypeError"
	       value="<?php echo Text::_('COM_SDAPROFILES_FITTING_TYPE_ERROR') ?>"
	/>

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
	</div>

	<input type="hidden" name="profileId" value="<?php echo $profile->sdaprofiles_profile_id; ?>" />
	<input type="hidden" id="sdaprofiles_fitting_id" name="sdaprofiles_fitting_id" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>