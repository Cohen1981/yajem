<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

\defined('_JEXEC') or die;

/* @var \Sda\Component\Sdajem\Site\Model\EventModel $item */

?>

<?php foreach ($this->items as $i => $item) : ?>
	<tr class="row<?php echo $i % 2; ?>">
		<th scope="row" class="has-context">
			<div>
				<?php echo $this->escape($item->title); ?>
			</div>
			<?php $editIcon = '<span class="fa fa-pencil-square mr-2" aria-hidden="true"></span>'; ?>
			<a class="hasTooltip" href="<?php echo Route::_('index.php?option=com_sdajem&task=event.edit&id=' . (int) $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape(addslashes($item->title)); ?>">
				<?php echo $editIcon; ?><?php echo $this->escape($item->title); ?></a>
		</th>
		<td class="d-none d-md-table-cell">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>


