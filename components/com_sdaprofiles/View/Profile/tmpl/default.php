<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

/** @var  FOF30\View\DataView\Raw  $this **/
/** @var  \Sda\Profiles\Admin\Model\Profile  $item */

?>

<div>
	<?php
	foreach ($this->getItems() as $item)
	{
		echo $item->sdaprofiles_profile_id . "<br/>";
		echo $item->address1 ."<br/>";
	}
	?>
</div>
