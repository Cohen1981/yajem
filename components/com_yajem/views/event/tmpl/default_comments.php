<?php
/**
 * @package     Yajem.Site
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

?>

<div id="yajem_comments">
    <?php if($this->eventParams->useUserProfile) : ?>
    <div id ="yajem_comment_grid" class="yajem_comment_grid">
    <?php endif; ?>
	<?php foreach ($this->comments as $i => $item) : ?>

	<?php
	if ($this->eventParams->useUserProfile)
	{
		echo '<img id="avatar_' . $item->id . '" class="yajem_comment_avatar yajem_img_round" src="' .
            $this->userProfiles[$item->userId]->avatar . '"/>';
	}
	?>

	<div id="output_<?php echo $item->id; ?>" class="yajem_comment_output">
		<div class="yajem_uname">
			<div class="yajem_pull_right">
				<?php if($item->userId == $this->eventParams->userId): ?>
					<a onclick="delComment(<?php echo $item->id; ?>)">
						<i class="fas fa-trash-alt" aria-hidden="true"></i>
					</a>
				<?php endif; ?>
			</div>
			<?php echo $this->userProfiles[$item->userId]->name ?>&nbsp;
			<?php
			$timestamp = new DateTime($item->timestamp);
			echo $timestamp->format('d.m.Y H:i')
			?>
		</div>
		<div class="yajem_comment"><?php echo nl2br($item->comment); ?></div>
	</div>

	<?php endforeach; ?>
	<?php if($this->eventParams->useUserProfile) : ?>
	</div>
    <?php endif; ?>
</div>

<form action="<?php echo JRoute::_('index.php?option=com_yajem&view=events'); ?>"  name="commentForm" id="commentForm" method="post">

	<div class="yajem_flex_row yajem-button-group">
		<textarea form="commentForm" id="comment" wrap="soft" name="comment"></textarea>
		<?php if ($this->eventParams->useAjaxCalls) : ?>
			<label id="yajem_comment" class="yajem_css_switch yajem_rounded" onclick="comment()">
				<?php echo JText::_('COM_YAJEM_SUBMIT_COMMENT') ?>
			</label>
		<?php else: ?>
			<label id="yajem_comment" class="yajem_css_switch yajem_rounded" for="commit_comment">
				<?php echo JText::_('COM_YAJEM_SUBMIT_COMMENT') ?>
			</label>
		<?php endif; ?>
	</div>

	<input type="radio" class="yajem_hidden" id="commit_comment" name="commit_comment" value="commit" onchange="commentForm.submit()" />
	<input id = "comment_userId" type="hidden" name="userId" value="<?php echo $this->eventParams->userId; ?>"/>
	<input id = "comment_eventId "type="hidden" name="eventId" value="<?php echo $this->event->id; ?>"/>
	<input id = "comment_task" type="hidden" name="task" value="event.saveComment"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
