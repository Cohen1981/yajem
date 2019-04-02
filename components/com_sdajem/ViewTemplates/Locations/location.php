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

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Location $location */
$location = $this->getModel('Location');
$this->addCssFile('media://com_sdajem/css/style.css');
$this->addJavascriptFile('media://com_sdajem/js/location.js');

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

<?php if ($location->image) :?>
	<div class="sdajem_image_container">
		<img src="<?php echo $location->image ?>" />
	</div>
<?php endif; ?>

<div class="sdajem_event_grid">

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
		<?php echo $location->contact->name; ?>
	</div>

</div>
<div id="map"></div>