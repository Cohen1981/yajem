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
$this->addCssFile('media://com_sdajem/css/sdajem_style.css');
$this->addJavascriptFile('media://com_sdajem/js/comments.js');

?>

<form action="<?php echo JRoute::_('index.php?option=com_sdajem&task=comment'); ?>" method="post"
      name="commentForm" id="commentForm">

	<label for="comment"></label>
	<textarea form="commentForm" id="comment" wrap="soft" name="comment"></textarea>

	<div id="comment_button" class="buttonsContainer">
		<button id="sdajem_comment_button"
		        type="button"
		        onclick="addCommentAjax()"
		        form="commentForm"
		>
			<i class="fas fa-comments" aria-hidden="true"></i>
			<?php echo Text::_('SDAJEM_NEW_COMMENT'); ?></button>
	</div>

	<div id="sdajem_comment_area" class="sdajem_comment_container">

		<?php
		foreach ($event->comments->sortByDesc('timestamp') as $comment)
		{
			$this->setModel('Comment', $comment);
			try
			{
				echo $this->loadAnyTemplate('site:com_sdajem/Comment/comment');
			}
			catch (Exception $e)
			{
			}
		}
		?>

	</div>
	<input type="hidden" name="eventId" value="<?php echo $event->sdajem_event_id ?>"/>
</form>