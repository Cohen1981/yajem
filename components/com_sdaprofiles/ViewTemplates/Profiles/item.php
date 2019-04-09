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

/** @var \Sda\Profiles\Site\View\Profile\Html $this */
/** @var \Sda\Profiles\Site\Model\Profile $profile */

$this->addCssFile('media://com_sdaprofiles/css/style.css');
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
<div id="profile_area" class="form-horizontal">

	<div class="control-group ">
		<label class="control-label">
			<?php echo Text::_('COM_SDAPROFILES_PROFILE_USERS_USER_ID_LABEL'); ?>
		</label>
		<div class="controls">
			<span>
				<?php echo $profile->user->username; ?>
			</span>
		</div>
	</div>
	<div class="control-group ">
		<label class="control-label">
			<?php echo Text::_('COM_SDAPROFILES_PROFILE_AVATAR_LABEL'); ?>
		</label>
		<div class="controls">
			<span>
				<img src="<?php echo $profile->avatar; ?>" class="sdaprofiles_avatar"/>
			</span>
		</div>
	</div>
	<div class="control-group ">
		<label class="control-label">
			<?php echo Text::_('COM_SDAPROFILES_PROFILE_ADDRESS_LABEL'); ?>
		</label>
		<div class="controls">
			<span>
				<?php
				echo $profile->address1 . "<br/>";
				echo $profile->address2 . "<br/>";
				echo $profile->postal . " " . $profile->city;
				?>
			</span>
		</div>
	</div>
	<div class="control-group ">
		<label class="control-label">
			<?php echo Text::_('COM_SDAPROFILES_PROFILE_CONTACT_LABEL'); ?>
		</label>
		<div class="controls">
			<span>
				<?php
				echo $profile->phone . "<br/>";
				echo $profile->mobil;
				?>
			</span>
		</div>
	</div>
	<div class="control-group ">
		<label class="control-label">
			<?php echo Text::_('COM_SDAPROFILES_PROFILE_DOB_LABEL'); ?>
		</label>
		<div class="controls">
			<span>
				<?php
				$dob = new \FOF30\Date\Date($profile->dob);
				echo $dob->format('d.m.Y');
				?>
			</span>
		</div>
	</div>

</div>
</div>
</div>

<div>
<input type="checkbox" id="fitting_switch" class="sdaprofiles_hidden" hidden onchange="switchCheckBox('fitting_switch')"/>
<div class="sdaprofiles_switchable">
<div class="well">
	<div class="titleContainer">
		<h1 class="page-title">
			<span class="icon-generic" aria-hidden="true"></span>
			<?php echo Text::_('COM_SDAPROFILES_TITLE_FITTING_BASIC'); ?>
		</h1>
	</div>
</div>

<div id="fitting_area" class="form-horizontal">
	<div id="sdaprofiles_fitting" class="control-group">
		<label class="control-label">
		</label>
		<div class="controls">
			<span class="sdaprofiles_fitting_cell"><?php echo Text::_('COM_SDAPROFILES_FITTING_TYPE_LABEL'); ?></span>
			<span class="sdaprofiles_fitting_cell"><?php echo Text::_('COM_SDAPROFILES_FITTING_DETAIL_LABEL'); ?></span>
			<span class="sdaprofiles_fitting_cell"><?php echo Text::_('COM_SDAPROFILES_FITTING_LENGHT_LABEL'); ?></span>
			<span class="sdaprofiles_fitting_cell"><?php echo Text::_('COM_SDAPROFILES_FITTING_WIDTH_LABEL'); ?></span>
		</div>
	</div>
	<?php if ($profile->fittings) : ?>
		<?php foreach ($profile->fittings as $fitting) : ?>
			<div id="sdaprofiles_fitting_<?php echo $fitting->sdaprofiles_fitting_id; ?>" class="control-group">
				<label class="control-label">
				</label>
				<div class="controls">
					<span class="sdaprofiles_fitting_cell"><?php echo $fitting->type ?></span>
					<span class="sdaprofiles_fitting_cell"><?php echo $fitting->detail ?></span>
					<span class="sdaprofiles_fitting_cell"><?php echo $fitting->length ?></span>
					<span class="sdaprofiles_fitting_cell"><?php echo $fitting->width ?></span>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
</div>
</div>

<div>
	<input type="checkbox" id="events_switch" class="sdaprofiles_hidden" hidden onchange="switchCheckBox('events_switch')"/>
	<div class="sdaprofiles_switchable">
	<?php
	if ($profile->attendees && (bool) $params->get('show_attendings_all'))
	{
	echo $this->loadAnyTemplate('site:com_sdajem/Attendee/attendings');
	}
	?>

	<?php
	if ($profile->organizing && (bool) $params->get('show_organizing_all'))
	{
		echo $this->loadAnyTemplate('site:com_sdajem/Events/organized');
	}
	?>
	</div>
</div>
