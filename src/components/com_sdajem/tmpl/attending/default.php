<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Sda\Component\Sdajem\Site\Model\AttendingModel;

defined('_JEXEC') or die();

$wa=$this->document->getWebAssetManager();
$wa->registerAndUseStyle('sdajem', 'com_sdajem/sdajem.css');

$canDo   = ContentHelper::getActions('com_sdajem', 'category', $this->item->catid);
$user = Factory::getApplication()->getIdentity();
try
{
	$canEdit = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $user->id);
}
catch (Exception $e)
{
}
$params = $this->item->params;

/* @var AttendingModel $item */
$item = $this->item;

?>

<div class="clearfix">
	<?php if (!empty($canEdit))
	{
		if ($canEdit) : ?>
			<div class="icons float-end">
				<?php echo HTMLHelper::_('sdajemIcon.editAttending', $item, $params); ?>
			</div>
		<?php endif;
	} ?>

	<div class="Attending">
		<p><?php echo $item->eventTitle; ?></p>
		<p><?php echo $item->attendeeName; ?></p>
		<p><?php echo Text::_($item->status->getAttendingStatusLabel()); ?></p>
	</div>
</div>
