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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

$app = Factory::getApplication();
$input = $app->input;

$this->useCoreUI = true;

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate')
	->useScript('com_sdajem.admin_event_letter');
$layout  = 'edit';
$tmpl = $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
?>

<form action="<?php echo Route::_('index.php?option=com_sdajem&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="event-form" class="form-validate">
    <div>
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'details']); ?>
		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', empty($this->item->id) ? Text::_('COM_FOOS_NEW_FOO') : Text::_('COM_FOOS_EDIT_FOO')); ?>
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
						<?php echo $this->getForm()->renderField('title'); ?>
						<?php echo $this->getForm()->renderField('alias'); ?>
						<?php echo $this->getForm()->renderField('access'); ?>
						<?php echo $this->getForm()->renderField('published'); ?>
						<?php echo $this->getForm()->renderField('publish_up'); ?>
						<?php echo $this->getForm()->renderField('publish_down'); ?>
						<?php echo $this->getForm()->renderField('catid'); ?>
                    </div>
                </div>
            </div>
        </div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>
		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
    </div>
	<input type="hidden" name="task" value="">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>