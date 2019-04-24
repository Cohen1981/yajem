<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;
use FOF30\Container\Container;
use Sda\Profiles\Admin\Model\FittingImage;
use Sda\Profiles\Site\Model\FittingType;

/** @var FOF30\View\DataView\Raw $this */
/** @var FittingType type */
/** @var FittingImage $image */
/** @var FittingImage $fImageModel */
/** @var FittingType $fTypeModel */

$this->addCssFile('media://com_sdaprofiles/css/style.css');
$this->addJavascriptFile('media://jui/js/jquery.min.js');
$this->addJavascriptFile('media://com_sdaprofiles/js/fittings.js');
$input = $this->input->getArray();

$profile = $this->getModel();
$fImageModel = $this->getContainer()->factory->model('FittingImage');
$fTypeModel = $this->getContainer()->factory->model('FittingType');
$fTypes = $fTypeModel->getItemsArray();

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
				<select id="sdaprofiles_input_type" name="type" required="required" onchange="checkType()">
					<?php
					/** @var FittingType $type */
					foreach ($fTypes as $type)
					{
						echo "<option id=\"$type->sdaprofiles_fitting_type_id\" 
							value=\"$type->sdaprofiles_fitting_type_id\" >$type->title</option>";
					}
					?>
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

		<?php foreach ($fTypes as $type) : ?>
		<input type="hidden" id="ft_<?php echo $type->sdaprofiles_fitting_type_id; ?>" name="fittingTypes" value="<?php echo $type->needSpace; ?>" />
		<?php $images = $fImageModel->where('type', '=', (int) $type->sdaprofiles_fitting_type_id)->get(); ?>
		<?php if (count($images) > 0) : ?>
		<div id="fitting_images_<?php echo $type->sdaprofiles_fitting_type_id ?>" class="control-group">
			<label class="control-label " for="sdaprofiles_input_image">
				<?php echo Text::_('COM_SDAPROFILES_FITTING_IMAGE_LABEL'); ?>
			</label>
			<div class="controls">
				<div id="chooseFittingImage" class="sdaprofiles_equipment_images">
					<?php
					foreach ($images as $image)
					{
						echo "<div class=\"sdaprofiles_equipment_cell\">";
						echo "<input type=\"radio\" id=\"fi_$image->sdaprofiles_fitting_image_id\" 
								name=\"image\" value=\"$image->sdaprofiles_fitting_image_id\">";
						echo "<label for=\"fi_$image->sdaprofiles_fitting_image_id\">
								<img src=\"$image->image\" class=\"img-label\" /></label>";
						echo "</div>";
					}
					?>
				</div>
				<span class="help-block">
					<?php echo Text::_('COM_SDAPROFILES_FITTING_IMAGE_DESC'); ?>
				</span>
			</div>
		</div>
		<?php endif; ?>
		<?php endforeach; ?>

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