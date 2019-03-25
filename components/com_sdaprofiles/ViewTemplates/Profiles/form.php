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
/** @var  \Sda\Profiles\Site\Model\Fitting $fitting */

echo $this->getRenderedForm();

$this->addCssFile('media://com_sdaprofiles/css/style.css');

$profile = $this->getItem();
?>

<form action="<?php echo JRoute::_('index.php?option=com_sdaprofiles&view=Profiles&task=edit&id=' . $profile->sdaprofiles_profile_id); ?>" method="post"
      name="adminForm" id="adminForm">

	<div class="well">
		<div class="titleContainer">
			<h1 class="page-title">
				<span class="icon-generic" aria-hidden="true"></span>
				<?php echo Text::_('COM_SDAPROFILES_TITLE_FITTING_BASIC'); ?>
			</h1>
		</div>
		<div class="buttonsContainer">
			<a class="btn btn-small button-new btn-success"
			   href="index.php?option=com_sdaprofiles&view=fittings&task=add&profileId=<?php echo $profile->sdaprofiles_profile_id ?>"
			>
				<i class="icon-new icon-white"></i>
				<?php echo Text::_('JNEW'); ?>
			</a>
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

		<?php if ($profile->fittings): ?>
		<?php foreach ($profile->fittings as $fitting): ?>

		<div>
			<a href="<?php echo JRoute::_('index.php?option=com_sdaprofiles&view=fittings&task=remove&id=' . $fitting->sdaprofiles_fitting_id) . '&' . JSession::getFormToken() .'=1';?>">
				<i class="icon-delete" aria-hidden="true"></i>
			</a>

		</div>
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
		<?php endif; ?>

	</div>
	<?php echo JHtml::_('form.token'); ?>

</form>
