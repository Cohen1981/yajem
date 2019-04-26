<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Location $location */

$location = $this->getModel('Location');
$this->addCssFile('media://com_sdajem/css/style.css');

if ($location->latlng)
{
	$lAddressString = urlencode($location->latlng);
}
else
{
	$lAddressString = urlencode($location->street) . "+" . urlencode($location->postalCode) . "+" . urlencode($location->city);
}

$currentUser = Factory::getUser();
if (ComponentHelper::isEnabled('com_sdaprofiles') && !$currentUser->guest)
{
	/** @var \Sda\Jem\Site\Model\User $userModel */
	$userModel = $this->getContainer()->factory->model('User');
	$userModel->load($currentUser->id);

	if ($userModel->profile)
	{
		$uAdressString = urlencode($userModel->profile->address1) . "+" .
			urlencode($userModel->profile->postal) . "+" .
			urlencode($userModel->profile->city);
	}
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_sdajem&view=Location&task=edit'); ?>" method="post"
	      name="locationForm" id="locationForm">
<div class="well">
	<div class="titleContainer">
		<h2 class="page-title">
			<span class="icon-compass" aria-hidden="true"></span>
			<?php echo $location->title; ?>
		</h2>
	</div>
	<?php if (!Factory::getUser()->guest): ?>
	<div class="buttonsContainer">
		<button type="submit"
		        value="<?php echo $location->sdajem_location_id; ?>"
		        form="locationForm"
		>
			<i class="icon-edit" aria-hidden="true"></i>
			<?php echo Text::_('JGLOBAL_EDIT'); ?>
		</button>
	</div>

	<?php endif; ?>
</div>
	<input type="hidden" name="id" value="<?php echo $location->sdajem_location_id; ?>"/>
</form>

<div class="sdajem_event_table">

	<div class="sdajem_event_grid">

		<div class="sdajem_label">
			<?php echo Text::_('COM_SDAJEM_LOCATION_CATEGORY_LABEL'); ?>
		</div>
		<div class="sdajem_value">
			<?php echo $location->category->title; ?>
		</div>
		<div class="sdajem_label">
			<?php echo Text::_('COM_SDAJEM_LOCATION_URL_LABEL'); ?>
		</div>
		<div class="sdajem_value">
			<a href="<?php echo $location->url; ?>" target="_blank"><?php echo $location->url; ?></a>
		</div>
		<div class="sdajem_label">
			<?php echo Text::_('COM_SDAJEM_LOCATION_ADDRESS_LABEL'); ?>
		</div>
		<div class="sdajem_value">
			<?php echo $location->street . "<br/>"; ?>
			<?php echo $location->postalCode . " "; ?>
			<?php echo $location->city; ?>
			<?php if ($location->latlng) {echo "<br/><br/>" . $location->latlng; } ?>
		</div>
		<div class="sdajem_label">
			<?php echo Text::_('COM_SDAJEM_LOCATION_DESCRIPTION_LABEL'); ?>
		</div>
		<div class="sdajem_value">
			<?php echo $location->description; ?>
		</div>
		<div class="sdajem_label">
			<?php echo Text::_('COM_SDAJEM_LOCATION_CONTACT_LABEL'); ?>
		</div>
		<div class="sdajem_value">
			<a href="index.php?option=com_contact&view=contact&id=<?php echo $event->host->id; ?>">
				<?php echo $location->contact->name; ?>
			</a>
		</div>

	</div>

	<?php if ($location->image) :?>
	<div class="sdajem_image_container">
		<img src="<?php echo $location->image ?>" />
	</div>
	<?php endif; ?>

</div>

<div class="sdajem_row">
	<a href="https://www.google.de/maps?q=<?php echo $lAddressString; ?>"
	   class="btn btn-default" target="_blank">
		<i class="fas fa-map-marked-alt" aria-hidden="true"></i>
		<?php echo Text::_('COM_SDAJEM_LOCATION_SHOW_ON_MAP'); ?>
	</a>

	<?php if ($uAdressString): ?>
		<a href="https://www.google.de/maps?saddr=<?php echo $uAdressString; ?>&daddr=<?php echo $lAddressString; ?>"
		   class="btn btn-default" target="_blank">
			<i class="fas fa-route" aria-hidden="true"></i>
			<?php echo Text::_('COM_SDAJEM_LOCATION_SHOW_ROUTE'); ?>
		</a>
	<?php endif; ?>
</div>
<!--<div id="map"></div>-->