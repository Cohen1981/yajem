<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Sda\Contacts\Admin\Model\Contact;
use Joomla\CMS\Language\Text;

/** @var \Sda\Contacts\Site\View\Contacts\Html $this */
/** @var Contact $contact */

$this->addCssFile('media://com_sdacontacts/css/style.css');

$contacts = $this->getItems();

?>

<div class="sdacontacts_container">

	<form action="<?php echo JRoute::_('index.php?option=com_sdacontacts&view=Contacts'); ?>" method="post"
	      name="adminForm" id="adminForm">

		<div class="sdacontacts_table">

			<div class="sdacontacts_header">
				<i class="fas fa-user" aria-hidden="true" title="COM_SDACONTACTS_ICON_NAME"> </i>
				<?php echo Text::_('COM_SDAPROFILES_CONTACT_NAME_LABEL'); ?>
			</div>
			<div class="sdacontacts_header">
				<i class="fas fa-image" aria-hidden="true" title="COM_SDACONTACTS_ICON_IMAGE"> </i>
				<?php echo Text::_('COM_SDAPROFILES_CONTACT_IMAGE_LABEL'); ?>
			</div>
			<div class="sdacontacts_header">
				<?php echo Text::_('COM_SDAPROFILES_CONTACT_ADDRESS_LABEL'); ?>
			</div>
			<div class="sdacontacts_header">
				<?php echo Text::_('COM_SDAPROFILES_CONTACT_CONTACT_LABEL'); ?>
			</div>

			<?php foreach ($contacts as $contact): ?>
			<div id="sdacontact_person" class="sdacontacts_cell">
				<i class="fas fa-user" aria-hidden="true" title="COM_SDACONTACTS_ICON_NAME"> </i>
				<a href="index.php?option=com_sdacontacts&view=Contacts&task=read&id=<?php echo $contact->sdacontacts_contact_id; ?>">
					<?php echo $contact->title; ?>
				</a><br/>
				<?php echo $contact->contactPerson; ?>
			</div>
			<div class="sdacontacts_cell">
				<i class="fas fa-image" aria-hidden="true" title="COM_SDACONTACTS_ICON_IMAGE"> </i>
				<img src="<?php echo $contact->image; ?>" class="sdacontact_contact_image_preview" alt=""/>
			</div>
			<div class="sdacontacts_address_cell">
				<i class="fas fa-home" aria-hidden="true"></i>
				<div class="align">
				<?php
					echo $contact->address . "<br/>";
					echo $contact->postcode . " " . $contact->city;
				?>
				</div>
			</div>
			<div class="sdacontacts_contact_cell">
				<i class="fas fa-phone" aria-hidden="true"></i>
				<div>
					<?php echo $contact->telephone; ?>
				</div>
				<i class="fas fa-mobile-alt" aria-hidden="true"></i>
				<div>
					<?php echo $contact->mobile; ?>
				</div>
				<i class="fas fa-envelope" aria-hidden="true"></i>
				<div>
					<?php echo $contact->email; ?>
				</div>
				<i class="fas fa-fax" aria-hidden="true"></i>
				<div>
					<?php echo $contact->fax; ?>
				</div>
			</div>
			<?php endforeach; ?>

		</div>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>
	</form>

</div>
