<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

/** @var \Sda\Profiles\Site\View\Fitting\Raw    $this       */
/** @var \Sda\Profiles\Site\Model\Fitting       $fitting    */
$input = $this->input->request->getArray();
$fitting = $this->getModel('Fitting');
if ($fitting->sdaprofiles_fitting_id == '')
{
	$fitting->load($input['id]']);
}
?>
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
