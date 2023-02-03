<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_sdajem
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
\defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');
$this->tab_name  = 'com-locations-form';
$this->ignore_fieldsets = ['details', 'item_associations', 'language'];
$this->useCoreUI = true;
?>
<form action="<?php echo Route::_('index.php?option=com_sdajem&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">
	<fieldset>
		<?php echo HTMLHelper::_('uitab.startTabSet', $this->tab_name, ['active' => 'details']); ?>
		<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'details', empty($this->item->id) ? Text::_('COM_FOOS_NEW_FOO') : Text::_('COM_FOOS_EDIT_FOO')); ?>
		<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php if (Multilanguage::isEnabled()) : ?>
			<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'language', Text::_('JFIELD_LANGUAGE_LABEL')); ?>
			<?php echo $this->form->renderField('language'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php else : ?>
			<?php echo $this->form->renderField('language'); ?>
		<?php endif; ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
		<?php echo HTMLHelper::_('form.token'); ?>
	</fieldset>
	<div class="mb-2">
		<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('location.save')">
			<span class="fas fa-check" aria-hidden="true"></span>
			<?php echo Text::_('JSAVE'); ?>
		</button>
		<button type="button" class="btn btn-danger" onclick="Joomla.submitbutton('location.cancel')">
			<span class="fas fa-times-cancel" aria-hidden="true"></span>
			<?php echo Text::_('JCANCEL'); ?>
		</button>
	</div>
</form>