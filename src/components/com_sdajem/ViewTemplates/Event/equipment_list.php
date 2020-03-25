<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use FOF30\Container\Container;
use Joomla\CMS\Language\Text;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */
$event = $this->getModel('Event');
$this->addCssFile('media://com_sdajem/css/sdajem_style.css');

if ($event->attendees)
{
	/** @var \Sda\Jem\Admin\Model\Attendee $attendee */
	foreach ($event->attendees as $attendee)
	{
		// Attendee has checked Equipment
		if (count($attendee->sdaprofilesFittingIds) > 0)
		{
			foreach ($attendee->sdaprofilesFittingIds as $fIds)
			{
				/** @var \Sda\Profiles\Admin\Model\Fitting $fitting */
				$fitting = Container::getInstance('com_sdaprofiles')->factory->model('Fitting');
				$fitting->load($fIds);

				if (!(bool) $fitting->typeModel->needSpace)
				{
					/** @var \Sda\Profiles\Admin\Model\FittingType $type */
					$type = $fitting->typeModel;
					/** @var \Sda\Profiles\Site\Model\Profile $profile */
					$profile = $fitting->profile;
					/** @var \Sda\Profiles\Site\Model\FittingImage $image */
					$image = $fitting->image;

					$listHtml = $listHtml . "<div class=\"sdajem_flex_row space_even\">";
					$listHtml = $listHtml . "<div class=\"sdajem_cell\">$profile->userName</div>";
					$listHtml = $listHtml . "<div class=\"sdajem_cell\">$fitting->detail</div>";
					$listHtml = $listHtml . "<div class=\"sdajem_cell\"><img class=\"sdajem_thumbnail\" src=\"$image->image\" alt=\"\" /></div>";
					$listHtml = $listHtml . "</div>";
				}
			}
		}
	}
}
?>
<div class="well">
	<div class="titleContainer">
		<h2 class="page-title">
			<i class="fas fa-cubes" aria-hidden="true"></i>
			<?php echo Text::_('COM_SDAJEM_TITLE_EQUIPMENT_BASIC'); ?>
		</h2>
	</div>
</div>

<div class="sdajem_flex_row space_even">
	<div class="sdajem_head sdajem_cell"> <?php echo Text::_('COM_SDAJEM_TITLE_ATTENDEES_BASIC'); ?> </div>
	<div class="sdajem_head sdajem_cell"> <?php echo Text::_('COM_SDAJEM_PROFILES_FITTING_DETAIL'); ?> </div>
	<div class="sdajem_head sdajem_cell"> <?php echo Text::_('COM_SDAJEM_PROFILES_FITTING_IMAGE'); ?> </div>
</div>
<?php echo $listHtml; ?>
