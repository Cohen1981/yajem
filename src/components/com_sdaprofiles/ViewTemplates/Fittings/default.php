<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use FOF30\Container\Container;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Sda\Html\Helper as HtmlHelper;

/** @var \Sda\Profiles\Site\View\Profile\Html $this */
/** @var \Sda\Profiles\Site\Model\Fitting $fitting */
/** @var \Sda\Profiles\Site\Model\FittingType $FType */
/** @var \Sda\Profiles\Site\Model\Profile $profile */

$this->addCssFile('media://com_sdaprofiles/css/sdaprofiles_style.css');
$this->addJavascriptFile('media://jui/js/jquery.min.js');
$this->addJavascriptFile('media://com_sdaprofiles/js/filter.js');

$items = $this->getItems();
$fTypeModel = $this->getContainer()->factory->model('FittingType');
$fTypes = $fTypeModel->getItemsArray();
$ProfileModel = $this->getContainer()->factory->model('Profile');
$profiles = $ProfileModel->getItemsArray();
?>

<form action="<?php echo JRoute::_('index.php?option=com_sdaprofiles&view=Fittings'); ?>" method="post"
      name="adminForm" id="adminForm">

	<div id="sdaprofiles_container">

		<!-- Filter -->
		<div><h3><?php Text::_("COM_SDAPROFILES_FILTER"); ?></h3></div>
		<div class="sdaprofiles_flex_row justify-left">
            <div class="sdaprofiles_cell default-grow">
                <?php
                $filters = $ProfileModel->getFilters('userName');
                echo HtmlHelper::getFilter('COM_SDAPROFILES_FITTINGS_OWNER', $filters);
                ?>
            </div>
            <div class="sdaprofiles_cell default-grow">
                <?php
                $filters = $fTypeModel->getFilters('title');
                echo HtmlHelper::getFilter('COM_SDAPROFILES_FITTINGTYPE_TITLE_LABEL', $filters);
                ?>
            </div>
		</div>

		<div class="sdaprofiles_section_container">

			<?php foreach ($this->items as $fitting) : ?>
                <?php
                $class = str_replace(' ', '_', $fitting->profile->userName);
                $class = $class . ' ' . str_replace(' ', '_', $fitting->typeModel->title);
                ?>
			<div class="sdaprofiles_flex_row filterRow <?php echo $class ?>">

				<div id="avatar_cell" class="sdaprofiles_cell">
					<?php if ($fitting->profile->user->id == Factory::getUser()->id) : ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_sdaprofiles&view=profiles&task=edit&id=' . (int) $fitting->profile->sdaprofiles_profile_id); ?>">
                    <?php else: ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_sdaprofiles&view=profiles&task=read&id=' . (int) $fitting->profile->sdaprofiles_profile_id); ?>">
                    <?php endif; ?>
					<?php
					if ($fitting->profile->avatar)
					{
						echo "<img class=\"sdaprofiles_avatar\" src=\"" . $fitting->profile->avatar . "\"/>";
					}
					else
					{
						echo "<img class=\"sdaprofiles_avatar\" src=\"media/com_sdaprofiles/images/user-image-blanco.png\"/>";
					}
					?>

					<?php echo $fitting->profile->userName; ?>
                    </a>
				</div>
                <div class="controls">
                    <div class="sdaprofiles_cell">
                        <?php echo $fitting->typeModel->title; ?>
                    </div>
                    <div class="sdaprofiles_cell">
                        <?php echo $fitting->detail; ?>
                    </div>
                    <div class="sdaprofiles_cell">
                        <?php echo $fitting->width . " x " . $fitting->length; ?>
                    </div>
                </div>
            </div>
			<?php endforeach; ?>
		</div>
	</div>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>


