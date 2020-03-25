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
use FOF30\Date\Date;

/** @var \Sda\Profiles\Site\Model\Profile $profile */
/** @var \Joomla\Input\Input $input */
/** @var \Sda\Jem\Site\Model\Attendee $attendee */
/** @var \Sda\Jem\Site\View\Attendee\Raw $this */

$this->addCssFile('media://com_sdaprofiles/css/sdaprofiles_style.css');
$this->addJavascriptFile('media://com_sdaprofiles/js/jquery-3.3.1.min.js');
$this->addJavascriptFile('media://com_sdaprofiles/js/fittings.js');

$input = $this->input->getArray();
if ($input['option'] == 'com_sdaprofiles' && ($input['task'] == 'edit' || $input['task'] == 'read'))
{
	$profile = $this->getModel();
}
$allowedUser = ($profile->users_user_id == Factory::getUser()->id) || ((bool) $profile->groupProfile || Factory::getUser()->authorise('core.admin'));
?>
<div class="well">
	<div class="titleContainer">
		<h2 class="page-title">
			<span class="icon-generic" aria-hidden="true"></span>
			<?php echo Text::_('COM_SDAPROFILES_TITLE_FITTINGS_STOCK'); ?>
		</h2>
	</div>
	<div class="buttonsContainer">
		<?php if ($allowedUser && $input['task'] == 'edit') : ?>
		<button id="sdaprofiles_fitting_new" type="button" onclick="newEquipment(<?php echo $profile->sdaprofiles_profile_id ?>)">
			<?php echo Text::_('SDAPROFILES_FITTING_NEW') ?>
		</button>
		<?php endif; ?>
	</div>
</div>

<div id="fitting_area" class="form-horizontal">
	<?php
	if ($profile->fittings)
	{
		foreach ($profile->fittings as $fitting)
		{
			$this->setModel('Fitting', $fitting);
			try
			{
				echo $this->loadAnyTemplate('site:com_sdaprofiles/Fitting/fitting');
			}
			catch (Exception $e)
			{
			}
		}
	}
	?>
</div>
