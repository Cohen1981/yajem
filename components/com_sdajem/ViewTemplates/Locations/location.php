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
$lAddressString = urlencode($location->street) . "+" . urlencode($location->postalCode) . "+" . urlencode($location->city);
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

/*http://maps.google.de/maps mit folgenden Parametern:

q     Suchanfrage, z. B. "50.1183,8.663" oder "Eiffel Tower" oder
		"Waldstr. 94, 63126 Dietzenbach, Germany" oder "http://...kml".
Achtung: Leerzeichen müssen durch + ersetzt und Umlaute UTF8-codiert werden:
		Ä = %C3%84; Ö = %C3%96; Ü = %C3%9C; ä = %C3%A4; ö = %C3%B6; ü = %C3%BC;
		ß = %C3%9F; & = %26; % = %25; = = %3D; ? = %3F; # = %23.
		Beispiel: "Waldstra%C3%9Fe+94,+63128+Dietzenbach,+Germany".
saddr Start-Adresse für Routenplanung (Syntax wie q).
		daddr Destination-Adresse für Routenplanung (Syntax wie q).
		t     Ansicht (Default=Karte, k=Satellit, h=Hybrid).
		z     Zoom (0=winzig, 12=Default=z.B. ganz Frankfurt, 21=z.B. Plattform des Eiffelturms).
		om    Mini-Navigation rechts unten (1=Default=Eingeschaltet, 0=Ausgeschaltet).
		ll    Position des Bildmittelpunkts, Latitude/Longitude in Grad Nord/Ost,
		z.B. "40.6891,-74.0447" für Freiheitsstatue.
spn   Zoom (mit anderer Syntax, nur gültig wenn Parameter z nicht angegeben).
		hl    Sprache, in der das Google-Fenster angezeigt wird (de=Default).
		ie    Zweck und Syntax unbekannt, wahrscheinlich Codierung der Suchanfrage (UTF8=Default).
		f     Zweck und Syntax unbekannt (p=Default).
		iwloc Zweck und Syntax unbekannt (A=Default).
*/
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