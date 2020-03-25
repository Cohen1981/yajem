<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \Sda\Jem\Site\View\Location\Html $this */
$this->addCssFile('media://com_sdajem/css/sdajem_style.css');

/** @var \Sda\Jem\Admin\Model\Location $location */
/** @var \Sda\Jem\Admin\Model\Location $model */
$model = $this->getModel();
$locations = $model->where('enabled', '=', 1)->orderBy('sdajem_category_id', 'ASC')->get();

?>

<form action="<?php echo JRoute::_('index.php?option=com_sdajem&view=Locations'); ?>" method="post" name="adminForm" id="adminForm">

	<div class="sdajem_flex_row">

		<div class="sdajem_cell sdajem_head">
			<i class="far fa-flag" aria-hidden="true" title="<?php echo Text::_('COM_SDAJEM_ICON_CATEGORY') ?>"></i>
			<?php echo Text::_('COM_SDAJEM_EVENT_SDAJEM_CATEGORY_ID_LABEL'); ?>
		</div>

		<div class="sdajem_cell sdajem_head">
			<i class="far fa-bookmark" aria-hidden="true" title="<?php echo Text::_('COM_SDAJEM_ICON_TITLE') ?>">&nbsp;</i>
			<?php echo Text::_('COM_SDAJEM_EVENT_TITLE_LABEL'); ?>
		</div>

		<div class="sdajem_cell sdajem_head">
			<i class="fas fa-map-marker-alt" aria-hidden="true" title="<?php echo Text::_('COM_SDAJEM_ICON_MAP') ?>">&nbsp;</i>
			<?php echo Text::_('COM_SDAJEM_LOCATION_CITY_LABEL'); ?>
		</div>

	</div>

	<?php foreach ($locations as $location) : ?>

	<div class="sdajem_flex_row">

		<div class="sdajem_cell">
			<i class="far fa-flag" aria-hidden="true" title="<?php echo Text::_('COM_SDAJEM_ICON_CATEGORY') ?>"></i>
			<?php echo $location->category->title; ?>
		</div>

		<div class="sdajem_cell">
			<i class="far fa-bookmark" aria-hidden="true" title="<?php echo Text::_('COM_SDAJEM_ICON_TITLE') ?>">&nbsp;</i>
			<a href="<?php echo Route::_('index.php?option=com_sdajem&view=Location&task=read&id=' . $location->sdajem_location_id) ?>">
				<?php echo $location->title; ?>
			</a>
		</div>

		<div class="sdajem_cell">
			<?php
			if ($location->city || $location->street)
			{
				echo "<i class=\"fas fa-map-marker-alt\" aria-hidden=\"true\" title=\"<?php echo Text::_('COM_SDAJEM_ICON_MAP') ?>\">&nbsp;</i>";
				echo "$location->street, $location->postalCode $location->city";
			}
			?>
		</div>

	</div>

	<?php endforeach; ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>
