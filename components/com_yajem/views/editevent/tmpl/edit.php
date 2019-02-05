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

// Getting params
$useHost			= (bool) JComponentHelper::getParams('com_yajem')->get('use_host');
$useOrganizer		= (bool) JComponentHelper::getParams('com_yajem')->get('use_organizer');
$useModalLocation	= (bool) JComponentHelper::getParams('com_yajem')->get('use_modal_location');

?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function () {

    });

    Joomla.submitbutton = function (task) {
        if (task == 'editevent.cancel') {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
        else {

            if (task != 'editevent.cancel' && document.formvalidator.isValid(document.id('item-form'))) {

                Joomla.submitform(task, document.getElementById('item-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form
        action="<?php echo JRoute::_('index.php?option=com_yajem&layout=edit&id=' . (int) $this->event->id); ?>"
        method="post" enctype="multipart/form-data" name="adminForm" id="item-form" class="form-validate">

    <div class="btn-toolbar">
        <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('editevent.save')">
                <span class="icon-ok"></span><?php echo JText::_('JSAVE') ?>
            </button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn" onclick="Joomla.submitbutton('editevent.cancel')">
                <span class="icon-cancel"></span><?php echo JText::_('JCANCEL') ?>
            </button>
        </div>
    </div>

	<?php echo $this->form->renderField('title'); ?>
	<?php echo $this->form->renderField('alias'); ?>

    <div class="form-horizontal">
		<?php echo HtmlHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo HtmlHelper::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_YAJEM_TITLE_EVENT', true)); ?>
        <div class="row-fluid">
            <div class="span16 form-horizontal">
                <fieldset class="adminform">

                    <input type="hidden" name="jform[id]" value="<?php echo $this->event->id; ?>"/>
                    <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>"/>

					<?php echo $this->form->renderField('catid'); ?>
	                <?php echo $this->form->renderField('access'); ?>
					<?php echo $this->form->renderField('url'); ?>

					<?php echo $this->form->renderField('allDayEvent'); ?>
					<?php echo $this->form->renderFieldset('duration'); ?>
					<?php echo $this->form->renderFieldset('detailed_duration'); ?>


					<?php if ($useModalLocation): ?>
						<?php echo $this->form->renderField('locationId'); ?>
					<?php else: ?>
						<?php echo $this->form->renderFieldset('location_list'); ?>
					<?php endif ?>
					<?php echo $this->form->renderField('description'); ?>

                </fieldset>
            </div>
        </div>
		<?php echo HtmlHelper::_('bootstrap.endTab'); ?>

		<?php if ($useHost || $useOrganizer): ?>
			<?php echo HtmlHelper::_('bootstrap.addTab', 'myTab', 'organization', JText::_('COM_YAJEM_TITLE_ORGANIZATION', true)); ?>
            <div class="row-fluid">
                <div class="span16 form-horizontal">
                    <fieldset class="adminform">

						<?php
						if ($useHost)
						{
							echo $this->form->renderFieldset('contact_host');
						}
						if ($useOrganizer)
						{
							echo $this->form->renderFieldset('contact_organizer');
						}
						?>
						<?php echo $this->form->renderFieldset('registration'); ?>

                    </fieldset>
                </div>
            </div>
			<?php echo HtmlHelper::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php echo HtmlHelper::_('bootstrap.addTab', 'myTab', 'image', JText::_('COM_YAJEM_EVENT_TAB_IMAGE')); ?>
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
                            <?php foreach ($this->event->attachments as $attachment):?>
                                <div id="<?php echo $attachment->id; ?>" class="yajem_attachment">
                                    <button onclick="delAttachment(<?php echo $attachment->id; ?>)">
                                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
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
		<?php echo HtmlHelper::_('bootstrap.addTab', 'myTab', 'inviting', JText::_('COM_YAJEM_EVENT_TAB_INVITING')); ?>
        <div class="row-fluid">
            <div class="span16 form-horizontal">
                <fieldset class="adminform">
					<?php echo $this->form->renderFieldset('inviteUsers'); ?>
                </fieldset>
            </div>
        </div>
		<?php echo HtmlHelper::_('bootstrap.endTab'); ?>

		<?php echo HtmlHelper::_('bootstrap.endTabSet'); ?>

        <div class="btn-toolbar">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('editevent.save')">
                    <span class="icon-ok"></span><?php echo JText::_('JSAVE') ?>
                </button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn" onclick="Joomla.submitbutton('editevent.cancel')">
                    <span class="icon-cancel"></span><?php echo JText::_('JCANCEL') ?>
                </button>
            </div>
        </div>

        <input type="hidden" name="task" value=""/>
		<?php echo HtmlHelper::_('form.token'); ?>

    </div>
</form>