<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Component\ComponentHelper;

/** @var \Sda\Profiles\Site\View\Profile\Html   $this       */
/** @var \Sda\Profiles\Site\Model\Profile       $profile    */
/** @var \Sda\Profiles\Site\Model\Fitting       $fitting    */

$this->addCssFile('media://com_sdaprofiles/css/sdaprofiles_style.css');
$this->addJavascriptFile('media://jui/js/jquery.min.js');
$this->addJavascriptFile('media://com_sda/js/tabSwitch.js');
$this->addJavascriptFile('media://com_sdaprofiles/js/profile.js');
$profile = $this->getItem();
$params = ComponentHelper::getParams('com_sdaprofiles');
$task = $this->input->get('task');

?>
	<label id="basic_switch_label" class="sda_tab sda_active" for="basic_switch">
		<?php echo Text::_('COM_SDAPROFILES_TITLE_PROFILES_READ') ?>
        <span id="basic_switch_state" class="fas fa-angle-double-up sdajem_float_right"></span>
	</label>

<div>
	<input type="checkbox" id="basic_switch" class="sdaprofiles_hidden sda_switchinputbox" hidden checked="checked"/>
	<div class="sda_switchable">
		<?php echo $this->getRenderedForm(); ?>
	</div>
</div>

<!-- Only User profiles are intially saved. If we have a group profile we don't have an profiles_id on add -->
<?php if (!($task === "newGroup")) : ?>
    <label id="fitting_switch_label" class="sda_tab" for="fitting_switch">
		<?php echo Text::_('COM_SDAPROFILES_TITLE_FITTING_BASIC') ?>
	    <span id="fitting_switch_reload" hidden><i class="fas fa-spinner fa-pulse"></i></span>
        <span id="fitting_switch_state" class="fas fa-angle-double-down sdajem_float_right"></span>
    </label>
	<div>
		<input type="checkbox" id="fitting_switch" class="sdaprofiles_hidden sda_switchinputbox" hidden/>
		<div class="sda_switchable">

			<?php
			try
			{
				echo $this->loadAnyTemplate('site:com_sdaprofiles/Fitting/fittings');
			}
			catch (Exception $e)
			{
			}
			?>

		</div>
	</div>

    <label id="events_switch_label" class="sda_tab" for="events_switch">
		<?php echo Text::_('COM_SDAJEM_EVENT_TITLE_LABEL') ?>
        <span id="events_switch_state" class="fas fa-angle-double-down sdajem_float_right"></span>
    </label>
	<div>
		<input type="checkbox" id="events_switch" class="sdaprofiles_hidden sda_switchinputbox" hidden/>
		<div class="sda_switchable">
			<?php
			if ($profile->attendees)
			{
				try
				{
					echo $this->loadAnyTemplate('site:com_sdajem/Attendee/attendings');
				}
				catch (Exception $e)
				{
				}
			}
			?>

			<?php
			if ($profile->organizing)
			{
				try
				{
					echo $this->loadAnyTemplate('site:com_sdajem/Events/organized');
				}
				catch (Exception $e)
				{
				}
			}
			?>
		</div>
	</div>

	<?php if ((bool) $params->get('use_preferences')) : ?>
        <label id="preferences_switch_label" class="sda_tab" for="preferences_switch">
		    <?php echo Text::_('') ?>
        </label>
		<div>
			<input type="checkbox" id="preferences_switch" class="sdaprofiles_hidden sda_switchinputbox" hidden/>
			<div class="sda_switchable">
				<?php
				try
				{
					echo $this->loadAnyTemplate('site:com_sdaprofiles/Profiles/preferences');
				}
				catch (Exception $e)
				{
				}
				?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
