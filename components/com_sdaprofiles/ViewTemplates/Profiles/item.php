<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;

/** @var \Sda\Profiles\Site\View\Profile\Html $this */
/** @var \Sda\Profiles\Site\Model\Profile $profile */

$this->addCssFile('media://com_sdaprofiles/css/style.css');
$items = $this->getItems();
echo $this->getRenderedForm();

?>

<?php if ($profile->fittings):?>

<div class="well">
	<div class="titleContainer">
		<h1 class="page-title">
			<span class="icon-generic" aria-hidden="true"></span>
			<?php echo Text::_('COM_SDAPROFILES_TITLE_FITTING_BASIC'); ?>
		</h1>
	</div>
</div>

<div class="sdaprofiles_fittings_grid">
	<div></div>
	<div class="sdaprofiles_cell">
		<?php echo Text::_('COM_SDAPROFILES_FITTING_TYPE_LABEL'); ?>
	</div>
	<div class="sdaprofiles_cell">
		<?php echo Text::_('COM_SDAPROFILES_FITTING_DETAIL_LABEL'); ?>
	</div>
	<div class="sdaprofiles_cell">
		<?php echo Text::_('COM_SDAPROFILES_FITTING_LW_LABEL'); ?>
	</div>


	<?php foreach ($profile->fittings as $fitting): ?>

		<div></div>
		<div class="sdaprofiles_cell">
			<a href="<?php echo JRoute::_('index.php?option=com_sdaprofiles&view=fittings&task=edit&id=' . $fitting->sdaprofiles_fitting_id);?>">
				<?php echo $fitting->type;?>
			</a>
		</div>
		<div class="sdaprofiles_cell">
			<?php echo $fitting->detail; ?>
		</div>
		<div class="sdaprofiles_cell">
			<?php echo $fitting->length . " x " . $fitting->width; ?>
		</div>

	<?php endforeach;?>

</div>

<?php endif;?>
