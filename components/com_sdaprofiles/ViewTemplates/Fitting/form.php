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
				<select id="sdaprofiles_input_type" name="type" required="required" onchange="checkType(this)">
					<option id="0" value="0" ><?php echo Text::_('COM_SDAPROFIES_FITTING_TYPE_SELECT'); ?></option>
					<option id="1" value="1"><?php echo Text::_('COM_SDAPROFIES_FITTING_TYPE_TENT'); ?></option>
					<!-- <option id="2" value="2"><?php // echo Text::_('COM_SDAPROFIES_FITTING_TYPE_SUNSAIL'); ?></option> -->
					<option id="3" value="3"><?php echo Text::_('COM_SDAPROFIES_FITTING_TYPE_MISC'); ?></option>
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

		<div id="fitting_tent_image" class="control-group" hidden>
			<label class="control-label " for="sdaprofiles_input_image">
				<?php echo Text::_('COM_SDAPROFILES_FITTING_IMAGE_LABEL'); ?>
			</label>
			<div class="controls">
				<div id="chooseFittingImage" class="sdaprofiles_equipment_images">
					<div class="sdaprofiles_equipment_cell">
						<input type="radio" id="alexb" name="image" value="media/com_sdaprofiles/images/alexb.png">
						<label for="alexb"><img src="media/com_sdaprofiles/images/alexb.png" class="img-label" /></label>
					</div>
					<div class="sdaprofiles_equipment_cell">
						<input type="radio" id="alexe" name="image" value="media/com_sdaprofiles/images/AlexE.png">
						<label for="alexe"><img src="media/com_sdaprofiles/images/AlexE.png" class="img-label" /></label>
					</div>
					<div class="sdaprofiles_equipment_cell">
						<input type="radio" id="arne" name="image" value="media/com_sdaprofiles/images/Arne.png">
						<label for="arne"><img src="media/com_sdaprofiles/images/Arne.png" class="img-label" /></label>
					</div>
					<div class="sdaprofiles_equipment_cell">
						<input type="radio" id="kaig" name="image" value="media/com_sdaprofiles/images/kaiG.png">
						<label for="kaig"><img src="media/com_sdaprofiles/images/kaiG.png" class="img-label" /></label>
					</div>
					<div class="sdaprofiles_equipment_cell">
						<input type="radio" id="kerstin" name="image" value="media/com_sdaprofiles/images/Kerstin.png">
						<label for="kerstin"><img src="media/com_sdaprofiles/images/Kerstin.png" class="img-label" /></label>
					</div>
					<div class="sdaprofiles_equipment_cell">
						<input type="radio" id="soeren" name="image" value="media/com_sdaprofiles/images/Soeren.png">
						<label for="soeren"><img src="media/com_sdaprofiles/images/Soeren.png" class="img-label" /></label>
					</div>
					<div class="sdaprofiles_equipment_cell">
						<input type="radio" id="winnie" name="image" value="media/com_sdaprofiles/images/Winnie.png">
						<label for="winnie"><img src="media/com_sdaprofiles/images/Winnie.png" class="img-label" /></label>
					</div>
					<div class="sdaprofiles_equipment_cell">
						<input type="radio" id="sonnensegel" name="image" value="media/com_sdaprofiles/images/Sonnensegel.png">
						<label for="sonnensegel"><img src="media/com_sdaprofiles/images/Sonnensegel.png" class="img-label" /></label>
					</div>
				</div>
				<span class="help-block">
					<?php echo Text::_('COM_SDAPROFILES_FITTING_IMAGE_DESC'); ?>
				</span>
			</div>
		</div>

		<div class="control-group ">
			<label class="control-label " for="sdaprofiles_input_detail">
				<?php echo Text::_('COM_SDAPROFILES_FITTING_DETAIL_LABEL'); ?>
			</label>
			<div class="controls">
				<input id="sdaprofiles_input_detail" type="text" name="detail" value="" />
				<span class="help-block">
					<?php echo Text::_('COM_SDAPROFILES_FITTING_DETAIL_DESC'); ?>
				</span>
			</div>
		</div>

		<div id="fitting_length" class="control-group">
			<label class="control-label " for="sdaprofiles_input_length">
				<?php echo Text::_('COM_SDAPROFILES_FITTING_LENGHT_LABEL'); ?>
			</label>
			<div class="controls">
				<input id="sdaprofiles_input_length" type="number" name="length" value="" />
				<span class="help-block">
					<?php echo Text::_('COM_SDAPROFILES_FITTING_LENGHT_DESC'); ?>
				</span>
			</div>
		</div>

		<div id="fitting_width" class="control-group ">
			<label class="control-label " for="sdaprofiles_input_width">
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
	<input type="hidden" id="formTask" name="formTask" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>