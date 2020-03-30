<?php

use Sda\Html\Helper as HtmlHelper;

/** @var \Sda\Profiles\Site\View\FittingType\Html  $this       */
/** @var \Sda\Profiles\Site\Model\FittingType      $fType      */

$fType = $this->getItem();

?>

<form id="adminForm"
      name="adminForm"
      action="<?php echo JRoute::_('index.php?option=com_sdaprofiles&view=FittingTypes'); ?>"
      method="post">

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="id" value="<?php echo $fType->sdaprofiles_fitting_type_id; ?>">
	<?php echo JHtml::_('form.token'); ?>
	<p>
		<?php
		echo $fType->title . " ";
		echo HtmlHelper::booleanIcon($fType->needSpace);
		?>
	</p>
</form>
