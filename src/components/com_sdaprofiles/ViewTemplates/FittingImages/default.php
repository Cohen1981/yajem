<?php

/** @var \Sda\Profiles\Site\View\FittingImage\Html $this */

use FOF30\Container\Container;
use Joomla\CMS\Language\Text;
use Sda\Html\Helper AS HtmlHelper;
use Sda\Profiles\Site\Model\FittingType;

/** @var \Sda\Profiles\Site\Model\FittingImage $fImage */
/** @var FittingType $fType */

$this->addCssFile('media://com_sdaprofiles/css/sdaprofiles_style.css');
$this->addJavascriptFile('media://com_sdaprofiles/js/jquery-3.3.1.min.js');
$this->addJavascriptFile('media://com_sdaprofiles/js/fittingImages.js');

$items = $this->getItems();

?>

<form id="adminForm"
      name="adminForm"
      action="<?php echo JRoute::_('index.php?option=com_sdaprofiles&view=FittingImages'); ?>"
      method="post">
    <input id="formTask" type="hidden" name="task" value=""/>
    <input id="formId" type="hidden" name="id" value="" />
	<?php echo JHtml::_('form.token'); ?>

    <?php foreach ($items as $fImage) : ?>
    <div id="sdaprofiles_message_container<?php echo $fImage->sdaprofiles_fitting_image_id; ?>"></div>
    <div id="sdap_fimage_<?php echo $fImage->sdaprofiles_fitting_image_id; ?>" class="sda_flexed_row">
        <div class="sdaprofiles_controls sda_flexed">
            <span id="edit_<?php echo $fImage->sdaprofiles_fitting_image_id; ?>"
                  class="sda_button"
                  onclick="document.getElementById('formTask').value='edit';
                           document.getElementById('formId').value=<?php echo $fImage->sdaprofiles_fitting_image_id; ?>;
                           document.getElementById('adminForm').submit();"
            >
                <?php echo HtmlHelper::getEditSymbol(); ?>
            </span>
            <span id="delete_<?php echo $fImage->sdaprofiles_fitting_image_id; ?>"
                 class="sda_button"
                 onclick="deleteFImage(<?php echo $fImage->sdaprofiles_fitting_image_id; ?>, false)"
            >
		        <?php echo HtmlHelper::getDeleteSymbol(); ?>
            </span>
        </div>
        <div class="sdaprofiles_image_cell sda_flexed">
            <?php
                echo HtmlHelper::imgTag($fImage->image, 'preview_image');
            ?>
        </div>
        <div class="sdaprofiles_detail_cell sda_flexed sda_flex2">
            <?php
            $fTypeID = (int) $fImage->type;
            $fType = Container::getInstance('com_sdaprofiles')->factory->model('FittingType');
            $fType->load($fTypeID);
            echo HtmlHelper::itemLink('com_sdaprofiles', 'FittingTypes', $fImage->sdaprofiles_fitting_image_id, false, $fType->title);
            echo "<p>" . $fImage->description . "</p>";

            if($needSpace = $fType->needSpace) {
                echo "<p>" . Text::_('COM_SDAPROFILES_FITTINGTYPE_NEEDSPACE_LABEL') . ": " . HtmlHelper::booleanIcon($needSpace) . "</p>";
            }
            ?>
        </div>
    </div>
    <?php endforeach; ?>

</form>