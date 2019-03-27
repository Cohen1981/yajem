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
/** @var \Sda\Profiles\Site\Model\Profile $profile */
/** @var  \Sda\Profiles\Site\Model\Fitting $fitting */

echo $this->getRenderedForm();

$this->addCssFile('media://com_sdaprofiles/css/style.css');
$this->addJavascriptFile('media://com_sdaprofiles/js/profile.js');

$profile = $this->getItem();
?>

<form name="fittingForm" id="fittingForm">

	<div class="well">
		<div class="titleContainer">
			<h1 class="page-title">
				<span class="icon-generic" aria-hidden="true"></span>
				<?php echo Text::_('COM_SDAPROFILES_TITLE_FITTING_BASIC'); ?>
			</h1>
		</div>
		<div class="buttonsContainer">
			<button type="button" form="fittingForm" name="fitting" onclick="addFitting()">
				<?php echo Text::_('SDAJEM_ADD_FITTING') ?>
			</button>
		</div>
	</div>

	<div class="sdajem_fitting_input">
		<label for="type">Type</label>
		<input type="text" id="type" name="type" value=""/>
		<label for="detail">Detail</label>
		<input type="text" id="detail" name="detail" value=""/>
		<label for="length">Length</label>
		<input type="number" id="length" name="length" value=""/>
		<label for="width">Width</label>
		<input type="number" id="width" name="width" value=""/>
	</div>

	<div id="fitting_area">
	<?php
	if ($profile->fittings)
	{
		foreach ($profile->fittings as $fitting)
		{
			echo $fitting->getHtml();
		}
	}
	?>
	</div>
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="profileId" value="<?php echo $profile->sdaprofiles_profile_id; ?>" />

</form>
