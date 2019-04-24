<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Factory;
use Sda\Profiles\Admin\Model\Profile;

/** @var \Sda\Profiles\Site\View\Fitting\Raw    $this       */
/** @var \Sda\Profiles\Site\Model\Fitting       $fitting    */
$this->addJavascriptFile('media://jui/js/jquery.min.js');
$this->addJavascriptFile('media://jui/js/bootstrap.min.js');
$this->addJavascriptFile('media://com_sdaprofiles/js/fittings.js');

$this->addCssFile('media://com_sdaprofiles/css/style.css');
$this->addCssFile('media://jui/css/bootstrap.min.css');

$input = $this->input->request->getArray();
$fitting = $this->getModel('Fitting');
if ($fitting->sdaprofiles_fitting_id == '')
{
	$fitting->load($input['id]']);
}
?>
<div id="sdaprofiles_fitting_<?php echo $fitting->sdaprofiles_fitting_id; ?>" class="control-group">
	<label class="control-label">
		<?php if ($fitting->image) : ?>
			<img src="<?php echo $fitting->image->image; ?>" class="image_smallest" />
		<?php endif; ?>
		<?php if ($fitting->profile->users_user_id == Factory::getUser()->id  ||
			($fitting->profile->users_user_id == null && !Factory::getUser()->guest)) : ?>
		<button type="button" onclick="editFittingAjax(<?php echo $fitting->sdaprofiles_fitting_id; ?>)">
			<i class="fas fa-edit" aria-hidden="true" title="<?php echo JText::_('COM_SDAPROFILES_ICON_EDIT') ?>"></i>
		</button>
		<button type="button" onclick="deleteFittingAjax(<?php echo $fitting->sdaprofiles_fitting_id; ?>)">
			<i class="fas fa-trash" aria-hidden="true" title="<?php echo JText::_('COM_SDAPROFILES_ICON_DELETE') ?>"></i>
		</button>
		<?php endif; ?>
	</label>
	<div class="controls">
		<span class="sdaprofiles_fitting_cell"><?php echo $fitting->getTypeString() ?></span>
		<span class="sdaprofiles_fitting_cell"><?php echo $fitting->detail ?></span>
		<span class="sdaprofiles_fitting_cell"><?php echo $fitting->length ?></span>
		<span class="sdaprofiles_fitting_cell"><?php echo $fitting->width ?></span>
	</div>
</div>
