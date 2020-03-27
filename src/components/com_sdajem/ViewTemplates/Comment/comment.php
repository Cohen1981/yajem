<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */
/** @var \Sda\Jem\Site\View\Comment\Raw    $this       */
/** @var \Sda\Jem\Site\Model\Comment       $comment    */

use FOF30\Date\Date;
use Joomla\CMS\Factory;

$comment = $this->getModel('Comment');
$timestamp = new Date($comment->timestamp);

?>

<div id="sdajem_comment_<?php echo $comment->sdajem_comment_id;?>" class="sdajem_comment_row">
	<div class="sdajem_comment_user">

		<?php if ($comment->user->profile) : ?>

		<div class='sdajem_avatar_container'>
			<img class="sdajem_avatar" src="<?php echo $comment->user->profile->avatar; ?>"/>
		</div>
		<div class='sdajem_profile_details'>
			<?php echo $comment->user->profile->userName . "<br/>" . $timestamp->format('d.m.Y H:i'); ?>
		</div>

		<?php else: ?>

		<div class='sdajem_profile_details'>
			<?php echo $comment->user->username . "<br/>" . $timestamp->format('d.m.Y H:i'); ?>
		</div>
		<?php endif; ?>

	</div>
	<div class="sdajem_comment_text">
		<?php echo nl2br($comment->comment); ?>
	</div>
	<div>
	<?php if ($comment->users_user_id == Factory::getUser()->id) : ?>
		<button type="button" onclick="deleteCommentAjax(<?php echo $comment->sdajem_comment_id; ?>)">
			<i class="fas fa-trash" aria-hidden="true"></i>
		</button>
	<?php endif; ?>
	</div>
</div>
