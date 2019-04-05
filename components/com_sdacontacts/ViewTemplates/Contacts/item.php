<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;

/** @var \Sda\Contacts\Site\View\Contacts\Html  $this       */
/** @var \Sda\Contacts\Admin\Model\Contact      $contact    */

$this->addCssFile('media://com_sdacontacts/css/style.css');
$contact = $this->getItem();

?>

<div class="sdacontacts_container">

	<form action="<?php echo JRoute::_('index.php?option=com_sdacontacts&view=Contacts'); ?>" method="post"
	      name="adminForm" id="adminForm">
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="id" value="<?php echo $contact->sdacontacts_contact_id; ?>">
		<?php echo JHtml::_('form.token'); ?>
	</form>

	<div class="sdacontacts_item_table">

		<div class="sdacontacts_label">
			<?php echo Text::_('COM_SDACONTACTS_CONTACT_CON_POSITION_LABEL'); ?>
		</div>
		<div class="sdacontacts_value">
			<?php echo $contact->con_position; ?>
		</div>
		<div class="sdacontacts_label">
			<?php echo Text::_('COM_SDAPROFILES_CONTACT_IMAGE_LABEL'); ?>
		</div>
		<div class="sdacontacts_value">
			<img src="<?php echo $contact->image ?>" class="sdacontacts_item_image" alt="" />
		</div>
		<div class="sdacontacts_label">
			<?php echo Text::_('COM_SDAPROFILES_CONTACT_ADDRESS_LABEL'); ?>
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
		<div class="sdacontacts_label">
			<?php echo Text::_('COM_SDAPROFILES_CONTACT_CONTACT_LABEL'); ?>
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
		<div class="sdacontacts_label">
			<?php echo Text::_('COM_SDACONTACTS_CONTACT_MISC_LABEL'); ?>
		</div>
		<div class="sdacontacts_value">
			<?php echo $contact->misc ?>
		</div>

	</div>

</div>
