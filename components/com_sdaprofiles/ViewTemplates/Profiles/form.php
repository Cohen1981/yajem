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
$profile = $this->getItem();
echo $this->getRenderedForm();

?>

<div class="sdaprofiles_fitting_container">

	<form name="fittingForm" id="fittingForm" class="form-horizontal">
		<div class="well">
			<div class="titleContainer">
				<h2 class="page-title">
					<span class="icon-generic" aria-hidden="true"></span>
					<?php echo Text::_('COM_SDAPROFILES_TITLE_FITTINGS_BASIC'); ?>
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

	<?php
	if ($profile->fittings)
	{
		echo $this->loadAnyTemplate('site:com_sdaprofiles/Fitting/fittings');
	}
	?>

	<?php
	if ($profile->attendees)
	{
		echo $this->loadAnyTemplate('site:com_sdajem/Attendee/attendings');
	}
	?>

	<?php
	if ($profile->organizing)
	{
		echo $this->loadAnyTemplate('site:com_sdajem/Events/organized');
	}
	?>

</div>
