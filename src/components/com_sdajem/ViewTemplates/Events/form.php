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
use Joomla\CMS\Component\ComponentHelper;
use FOF30\Container\Container;
use Sda\Jem\Admin\Model\Event;
use Sda\Profiles\Admin\Model\Profile;

/** @var \Sda\Jem\Site\View\Event\Html   $this       */

$this->addCssFile('media://com_sdajem/css/sdajem_style.css');
$this->addJavascriptFile('media://com_sdajem/js/jquery-3.3.1.min.js');
$this->addJavascriptFile('media://com_sdajem/js/eventForm.js');

/** @var Event $event */
$event = $this->getItem();
echo $this->getRenderedForm();

$plTool = ComponentHelper::getParams('com_sdajem')->get('usePlaningTool');

echo "<input type=\"hidden\" id='usePlaningTool' name=\"usePlaningTool\" class='params' value=\"$plTool\" />";

?>

<?php if (ComponentHelper::isEnabled('com_sdaprofiles')) : ?>
<div class="control-group ">
	<label class="control-label " for="useFittings"><?php echo Text::_('COM_SDAJEM_USEFITTINGS_LABEL'); ?></label>
	<div class="controls">
		<select id="useFittings" name="useFittings">
			<option value="1" <?php if($event->useFittings == 1) {echo "selected=\"selected\"";} ?>>
				<?php echo Text::_('JYES'); ?>
			</option>
			<option value="0" <?php if($event->useFittings == 0) {echo "selected=\"selected\"";} ?>>
				<?php echo Text::_('JNO'); ?>
			</option>
		</select>
		<span class="help-block"><?php echo Text::_('COM_SDAJEM_USEFITTINGS_DESC'); ?></span>
	</div>
</div>

<div class="control-group ">
	<label class="control-label " for="fittingProfile"><?php echo Text::_('COM_SDAJEM_FITTINGPROIFLE_LABEL'); ?></label>
	<div class="controls">
		<select name="fittingProfile" id="fittingProfile">
			<option value=""><?php echo Text::_('COM_SDAJEM_NONE_SELECTED'); ?></option>
			<?php
			$profiles = Profile::getGroupProfiles();

			if (count($profiles) > 0)
			{
				/** @var Profile $profile */
				foreach ($profiles as $profile)
				{
					echo "<option value=\"$profile->sdaprofiles_profile_id\"";

					if ($event->fittingProfile == $profile->sdaprofiles_profile_id || (! $event->fittingProfile && (bool) $profile->defaultGroup))
					{
						echo "selected=\"selected\"";
					}
					echo ">$profile->userName</option>";
				}
			}
			?>
		</select>
	</div>
</div>
<?php endif; ?>

<?php if (JPluginHelper::isEnabled('system', 'sdamailer') && $this->task == 'edit') : ?>
	<div class="control-group ">
		<label class="control-label " for="important_change"><b><?php echo Text::_('COM_SDAJEM_IMPORTANT_CHANGE'); ?></b></label>
		<div class="controls">
			<input type="checkbox" name="important_change" id="important_change" />
			<span class="help-block"><?php echo Text::_('COM_SDAJEM_IMPORTANT_CHANGE_DESC'); ?></span>
		</div>
	</div>
<?php endif; ?>
