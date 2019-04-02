<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */
$event = $this->getModel('Event');
$this->addCssFile('media://com_sdajem/css/style.css');
$this->addJavascriptFile('media://com_sdajem/js/comments.js');

?>

<form action="<?php echo JRoute::_('index.php?option=com_sdajem&task=comment'); ?>" method="post"
      name="commentForm" id="commentForm">
	<div class="well">
		<div class="titleContainer">
			<h2 class="page-title">
				<span class="icon-comments-2" aria-hidden="true"></span>
				<?php echo Text::_('COM_SDAJEM_TITLE_COMMENTS_BASIC'); ?>
			</h2>
		</div>
		<div class="buttonsContainer">
			<button id="sdajem_comment_button"
					type="button"
			        onclick="addCommentAjax()"
			        form="commentForm"
			>
				<i class="fas fa-comments" aria-hidden="true"></i>
				<?php echo Text::_('SDAJEM_NEW_COMMENT'); ?></button>
		</div>
	</div>

	<textarea form="commentForm" id="comment" wrap="soft" name="comment"></textarea>

	<div id="sdajem_comment_area" class="sdajem_comment_container">

		<?php
		foreach ($event->comments->sortByDesc('timestamp') as $comment)
		{
			$this->setModel('Comment', $comment);
			echo $this->loadAnyTemplate('site:com_sdajem/Comment/comment');
		}
		?>

	</div>
	<input type="hidden" name="eventId" value="<?php echo $event->sdajem_event_id ?>"/>
</form>