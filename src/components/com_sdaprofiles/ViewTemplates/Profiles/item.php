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

$this->addJavascriptFile('media://com_sda/js/jquery-3.3.1.min.js');
$this->addJavascriptFile('media://com_sda/js/tabSwitch.js');
$this->addCssFile('media://com_sdaprofiles/css/sdaprofiles_style.css');
$profile = $this->getItem();

$params = ComponentHelper::getParams('com_sdaprofiles');

?>
<form id="adminForm" name="adminForm">
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="profileId" value="<?php echo $profile->sdaprofiles_profile_id; ?>"/>
</form>

<label id="basic_switch_label" class="sda_tab sda_active" for="basic_switch">
	<?php echo Text::_('COM_SDAPROFILES_TITLE_PROFILES_READ') ?>
    <span id="basic_switch_state" class="fas fa-angle-double-up float-right"></span>
</label>

<div>
    <input type="checkbox" id="basic_switch" class="sdaprofiles_hidden sda_switchinputbox" hidden checked="checked"/>
    <div class="sda_switchable">
        <div id="profile_area" class="form-horizontal">

            <div class="control-group ">
                <label class="control-label">
					<?php echo Text::_('COM_SDAPROFILES_PROFILE_USERS_USER_ID_LABEL'); ?>
                </label>
                <div class="controls">
			<span>
				<?php echo $profile->userName; ?>
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
			<?php if ($profile->users_user_id) : ?>
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
					echo $profile->mobil . "<br/>";
					echo $profile->user->email;
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
			<?php endif; ?>

        </div>
    </div>
</div>

<div>
    <label id="fitting_switch_label" class="sda_tab" for="fitting_switch">
		<?php echo Text::_('COM_SDAPROFILES_TITLE_FITTING_BASIC') ?>
        <span id="fitting_switch_state" class="fas fa-angle-double-down float-right"></span>
    </label>
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

<?php if ($profile->users_user_id) : ?>
    <label id="events_switch_label" class="sda_tab" for="events_switch">
		<?php echo Text::_('COM_SDAJEM_EVENT_TITLE_LABEL') ?>
        <span id="fitting_switch_state" class="fas fa-angle-double-down float-right"></span>
    </label>
    <div>
        <input type="checkbox" id="events_switch" class="sdaprofiles_hidden sda_switchinputbox" hidden/>
        <div class="sda_switchable">
			<?php
			if ($profile->attendees && (bool) $params->get('show_attendings_all'))
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
			if ($profile->organizing && (bool) $params->get('show_organizing_all'))
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
<?php endif; ?>
