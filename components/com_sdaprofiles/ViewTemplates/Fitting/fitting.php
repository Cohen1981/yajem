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
$fitting->load($input['id]']);
?>

<div id="sdaprofiles_fitting_<?php echo $fitting->sdaprofiles_fitting_id; ?>" class="sdaprofiles_flex_row">
	<div class="sdaprofiles_cell">
		<button type="button" onclick="deleteFittingAjax(<?php echo $fitting->sdaprofiles_fitting_id; ?>)">
			<i class="far fa-bookmark" aria-hidden="true" title="<?php echo JText::_('COM_SDAPROFILES_ICON_DELETE') ?>">&nbsp;</i>
		</button>
	</div>
	<div class="sdaprofiles_cell"><?php echo $fitting->type ?></div>
	<div class="sdaprofiles_cell"><?php echo $fitting->detail ?></div>
	<div class="sdaprofiles_cell"><?php echo $fitting->length ?></div>
	<div class="sdaprofiles_cell"><?php echo $fitting->width ?></div>
</div>
