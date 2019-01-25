<?php
/**
 * @package     Yajem.Administrator
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

HtmlHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HtmlHelper::_('behavior.tooltip');
HtmlHelper::_('behavior.formvalidation');
HtmlHelper::_('formbehavior.chosen', 'select');
HtmlHelper::_('behavior.keepalive');

$useContact	= (bool) JComponentHelper::getParams('com_yajem')->get('use_location_contact');
$googleApiKey = (string) JComponentHelper::getParams('com_yajem')->get('global_googleapi');

?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {

	});

	Joomla.submitbutton = function (task) {
		if (task == 'editlocation.cancel') {
			Joomla.submitform(task, document.getElementById('item-form'));
		}
		else {

			if (task != 'editlocation.cancel' && document.formvalidator.isValid(document.id('item-form'))) {

				Joomla.submitform(task, document.getElementById('item-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>
<?php if ($googleApiKey): ?>
	<?php
		HtmlHelper::_('script', 'com_yajem/googleMaps.js', array('version' => 'auto', 'relative' => true));
		echo '<script src="https://maps.googleapis.com/maps/api/js?key=' . $googleApiKey . '&callback=initMap" async defer></script>'
	?>
<?php endif; ?>


<form
		action="<?php echo JRoute::_('index.php?option=com_yajem&layout=edit&id=' . (int) $this->location->id); ?>"
		method="post" enctype="multipart/form-data" name="adminForm" id="item-form" class="form-validate">

	<?php echo $this->form->renderField('title'); ?>

	<div class="form-horizontal">
		<?php echo HtmlHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo HtmlHelper::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_YAJEM_TITLE_LOCATION', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

					<input type="hidden" name="jform[id]" value="<?php echo $this->location->id; ?>"/>
					<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>"/>
					<input type="hidden" name="jform[latlng]" id="jform_latlng" value="<?php echo $this->location->latlng; ?>"/>

					<?php echo $this->form->renderField('catid'); ?>
					<?php echo $this->form->renderField('url'); ?>

					<?php echo $this->form->renderField('street'); ?>
					<?php echo $this->form->renderField('postalCode'); ?>
					<?php echo $this->form->renderField('city'); ?>
					<?php echo $this->form->renderField('stateAddress'); ?>

					<?php if ($googleApiKey): ?>
					<div class="control-group">
						<div class="control-label">
							<input id="submit" type="button" value="Geocode">
						</div>
						<div class="controls">
							<div id="map" style="height: 300px; width: 100%;"></div>
						</div>
					</div>
					<?php endif; ?>

					<?php if ($useContact): ?>
						<?php echo $this->form->renderFieldset('basic');  ?>
					<?php endif; ?>

					<?php echo $this->form->renderField('description'); ?>

				</fieldset>
			</div>
		</div>
		<?php echo HtmlHelper::_('bootstrap.endTab'); ?>

		<?php echo HtmlHelper::_('bootstrap.addTab', 'myTab', 'image', JText::_('COM_YAJEM_LOCATION_TAB_IMAGE')); ?>
		<fieldset class="adminform">
			<div class="row-fluid">
				<div class="span16">
					<?php echo $this->form->renderFieldset('image');  ?>
					<?php echo $this->form->renderFieldset('attachments');  ?>
                    <div class="control-group">
                        <div class="control-label">
							<?php echo Text::_('COM_YAJEM_ATTACHED') ?>
                        </div>
                        <div class="controls">
							<?php foreach ($this->location->attachments as $attachment):?>
                                <div id="<?php echo $attachment->id; ?>" class="yajem_attachment">
                                    <button onclick="delAttachment(<?php echo $attachment->id; ?>)">
                                        <i class="fas fa-trash-alt" aria-hidden="true">&nbsp;</i>
                                    </button>
									<?php echo $attachment->title; ?>
                                </div>
							<?php endforeach; ?>
                        </div>
                    </div>
				</div>
			</div>
		</fieldset>
		<?php echo HtmlHelper::_('bootstrap.endTab'); ?>

		<?php echo HtmlHelper::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_YAJEM_TITLE_PUBLISHING', true)); ?>
		<?php echo $this->form->renderField('published'); ?>
		<?php echo $this->form->renderField('created_by'); ?>
		<?php echo $this->form->renderField('modified_by'); ?>
		<?php echo HtmlHelper::_('bootstrap.endTab'); ?>

		<?php echo HtmlHelper::_('bootstrap.endTabSet'); ?>

    </div>
    <div class="btn-toolbar">
        <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('editlocation.save')">
                <span class="icon-ok"></span><?php echo JText::_('JSAVE') ?>
            </button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn" onclick="Joomla.submitbutton('editlocation.cancel')">
                <span class="icon-cancel"></span><?php echo JText::_('JCANCEL') ?>
            </button>
        </div>
    </div>

    <input type="hidden" name="task" />
	<?php echo JHtml::_('form.token'); ?>

	</div>
</form>