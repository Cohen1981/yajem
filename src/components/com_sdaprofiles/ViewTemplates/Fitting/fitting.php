<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Sda\Profiles\Admin\Model\Profile;

/** @var \Sda\Profiles\Site\View\Fitting\Raw    $this       */
/** @var \Sda\Profiles\Site\Model\Fitting       $fitting    */
$this->addJavascriptFile('media://jui/js/jquery.min.js');
$this->addJavascriptFile('media://jui/js/bootstrap.min.js');
$this->addJavascriptFile('media://com_sdaprofiles/js/fittings.js');

$this->addCssFile('media://com_sdaprofiles/css/sdaprofiles_style.css');
$this->addCssFile('media://jui/css/bootstrap.min.css');

$input = $this->input->request->getArray();
$fitting = $this->getModel('Fitting');
if ($fitting->sdaprofiles_fitting_id == '')
{
	$fitting->load($input['id]']);
}
$allowedUser = ($fitting->profile->users_user_id == Factory::getUser()->id) || ((bool) $fitting->profile->groupProfile || Factory::getUser()->authorise('core.admin'));
?>
<div id="sdaprofiles_fitting_<?php echo $fitting->sdaprofiles_fitting_id; ?>" class="control-group">
	<label class="control-label">
		<?php if ($fitting->image->image != '') : ?>
			<img src="<?php echo $fitting->image->image; ?>" class="image_smallest" />
		<?php endif; ?>
		<?php if ($allowedUser) : ?>
		<button type="button" onclick="editFittingAjax(<?php echo $fitting->sdaprofiles_fitting_id; ?>)">
			<i class="fas fa-edit" aria-hidden="true" title="<?php echo JText::_('COM_SDAPROFILES_ICON_EDIT') ?>"></i>
		</button>
		<button type="button" onclick="deleteFittingAjax(<?php echo $fitting->sdaprofiles_fitting_id; ?>)">
			<i class="fas fa-trash" aria-hidden="true" title="<?php echo JText::_('COM_SDAPROFILES_ICON_DELETE') ?>"></i>
		</button>
		<?php endif; ?>
	</label>
	<div class="controls">
		<span class="sdaprofiles_fitting_cell">
		<?php
		if ($fitting->standard)
		{
			echo "<i id=\"defaultMarker\" class=\"fas fa-star\" aria-hidden=\"true\" title=\"\">&nbsp;</i>";
		}

		echo $fitting->typeModel->title;
		?>
		</span>
		<span class="sdaprofiles_fitting_cell"><?php echo $fitting->detail ?></span>
		<span class="sdaprofiles_fitting_cell">
            <?php if ((bool) $fitting->typeModel->needSpace) { echo Text::_('COM_SDAPROFILES_FITTING_LENGTH_LABEL') . ": " . $fitting->length; } ?>
        </span>
		<span class="sdaprofiles_fitting_cell">
            <?php if ((bool) $fitting->typeModel->needSpace) { echo Text::_('COM_SDAPROFILES_FITTING_LENGTH_DESC') . ": " . $fitting->width; } ?>
        </span>
	</div>
</div>
