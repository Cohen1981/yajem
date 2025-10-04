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
use Sda\Component\Sdajem\Site\Model\UserModel;

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

/* @var \Sda\Component\Sdajem\Site\Model\LocationModel $item */
$item = $this->item;

if ($item->latlng)
{
	$lAddressString = urlencode($item->latlng);
}
else
{
	$lAddressString = urlencode($item->street) . "+" . urlencode($item->postalCode) . "+" . urlencode($item->city);
}

$uAdressString = null;

if (!$user->guest)
{
	/** @var UserModel $userModel */
	$userModel = new UserModel($user->id);

	$uAdressString = urlencode($userModel->profile['address1']) . "+" .
		urlencode($userModel->profile['postal_code']) . "+" .
		urlencode($userModel->profile['city']);
}

?>

<div class="clearfix">
	<?php if (!empty($canEdit))
	{
		if ($canEdit) : ?>
			<div class="icons float-end">
				<?php echo HTMLHelper::_('sdajemIcon.editLocation', $item, $params); ?>
			</div>
		<?php endif;
	} ?>
	<p>
		<a href="https://www.google.de/maps?q=<?php echo $lAddressString; ?>"
		   class="me-2" target="_blank">
			<i class="fas fa-map-marked-alt" aria-hidden="true"></i>
			<?php echo Text::_('COM_SDAJEM_LOCATION_SHOW_ON_MAP'); ?>
		</a>

		<?php if ($uAdressString): ?>
			<a href="https://www.google.de/maps?saddr=<?php echo $uAdressString; ?>&daddr=<?php echo $lAddressString; ?>"
			   class="me-2" target="_blank">
				<i class="fas fa-route" aria-hidden="true"></i>
				<?php echo Text::_('COM_SDAJEM_LOCATION_SHOW_ROUTE'); ?>
			</a>
		<?php endif; ?>
	</p>

	<?php if ($item->image): ?>
		<div class="sdajem_teaser_image">
			<?php echo HTMLHelper::image($item->image,'',['class'=>'float-start pe-2']); ?>
		</div>
	<?php endif; ?>
	<div class="loc_address">
		<p><?php echo $item->street; ?></p>
		<p><?php echo $item->postalCode; ?></p>
		<p><?php echo $item->city; ?></p>
	</div>
</div>
