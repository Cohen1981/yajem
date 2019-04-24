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

$this->addCssFile('media://com_sdaprofiles/css/style.css');
$this->addJavascriptFile('media://jui/js/jquery.min.js');
$this->addJavascriptFile('media://com_sdaprofiles/js/profile.js');
$profile = $this->getItem();
$params = ComponentHelper::getParams('com_sdaprofiles');

?>
<div class="sdaprofiles_tabs">
	<label id="basic_switch_label" class="sdaprofile_tab sda_active" for="basic_switch">
		<?php echo Text::_('COM_SDAPROFILES_TITLE_PROFILES_READ') ?>
	</label>
	<label id="fitting_switch_label" class="sdaprofile_tab" for="fitting_switch">
		<?php echo Text::_('COM_SDAPROFILES_TITLE_FITTING_BASIC') ?>
	</label>
	<label id="events_switch_label" class="sdaprofile_tab" for="events_switch">
		<?php echo Text::_('COM_SDAJEM_EVENT_TITLE_LABEL') ?>
	</label>
	<?php if ((bool) $params->get('use_preferences')) : ?>
	<label id="preferences_switch_label" class="sdaprofile_tab" for="preferences_switch">
		<?php echo Text::_('') ?>
	</label>
	<?php endif;?>
</div>

<div>
<input type="checkbox" id="basic_switch" class="sdaprofiles_hidden" hidden checked="checked" onchange="switchCheckBox('basic_switch')"/>
<div class="sdaprofiles_switchable">
	<?php echo $this->getRenderedForm(); ?>
</div>
</div>

<div>
<input type="checkbox" id="fitting_switch" class="sdaprofiles_hidden" hidden onchange="switchCheckBox('fitting_switch')"/>
<div class="sdaprofiles_switchable">

	<?php
		echo $this->loadAnyTemplate('site:com_sdaprofiles/Fitting/fittings');
	?>
	<?php
		echo $this->loadAnyTemplate('site:com_sdaprofiles/Fitting/form');
	?>

</div>
</div>

<div>
<input type="checkbox" id="events_switch" class="sdaprofiles_hidden" hidden onchange="switchCheckBox('events_switch')"/>
<div class="sdaprofiles_switchable">
	<?php
	if ($profile->attendees)
	{
		echo $this->loadAnyTemplate('site:com_sdajem/Attendee/attendings');
	}
	?>

	<?php
	if ($profile->organizing)
	{
		echo $this->loadAnyTemplate('site:com_sdajem/Events/organized');
	}
	?>
</div>
</div>

<?php if ((bool) $params->get('use_preferences')) : ?>
<div>
	<input type="checkbox" id="preferences_switch" class="sdaprofiles_hidden" hidden onchange="switchCheckBox('preferences_switch')"/>
	<div class="sdaprofiles_switchable">
		<?php echo $this->loadAnyTemplate('site:com_sdaprofiles/Profiles/preferences'); ?>
	</div>
</div>
<?php endif; ?>
