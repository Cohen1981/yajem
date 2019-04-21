<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;
use FOF30\Date\Date;

/** @var \Sda\Profiles\Site\Model\Profile $profile */
/** @var \Joomla\Input\Input $input */
/** @var \Sda\Jem\Site\Model\Attendee $attendee */
/** @var \Sda\Jem\Site\View\Attendee\Raw $this */

$this->addCssFile('media://com_sdaprofiles/css/style.css');
$this->addJavascriptFile('media://com_sdaprofiles/js/fittings.js');

$input = $this->input->getArray();
if ($input['option'] == 'com_sdaprofiles' && $input['task'] == 'edit')
{
	$profile = $this->getModel();
}
?>
<div class="well">
	<div class="titleContainer">
		<h2 class="page-title">
			<span class="icon-generic" aria-hidden="true"></span>
			<?php echo Text::_('COM_SDAPROFILES_TITLE_FITTINGS_STOCK'); ?>
		</h2>
	</div>
	<div class="buttonsContainer">
		<button id="sdaprofiles_fitting_new" type="button" onclick="newEquipment()">
			<?php echo Text::_('SDAPROFILES_FITTING_NEW') ?>
		</button>
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
	<?php
	if ($profile->fittings)
	{
		foreach ($profile->fittings as $fitting)
		{
			$this->setModel('Fitting', $fitting);
			echo $this->loadAnyTemplate('site:com_sdaprofiles/Fitting/fitting');
		}
	}
	?>
</div>
