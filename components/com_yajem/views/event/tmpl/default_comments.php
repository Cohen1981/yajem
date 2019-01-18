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

use Joomla\CMS\Factory;

$user	= Factory::getUser();
$userId = $user->get('id');
$guest	= $user->guest;

?>

<div id="comments" class="yajem_grid_section">
	<?php foreach ($this->comments as $i => $item) : ?>

    <?php
        // Which Avatar to use
        if ($item->avatar) {
            $avatar = '<img class="yajem_avatar yajem_img_round" src="' . $item->avatar . '"/>';
        } else {
            $avatar = '<img class="yajem_avatar" src="'. JURI::root() . '/media/com_yajem/images/user-image-blanco.png"/>';
        }
        //User Name
        if ($item->clearName) {
            $userName = $item->clearName;
        } else {
            $userName = $item->attendee;
        }
	?>

    <div class="yajem_label">
        <div class="yajem_avatar_container">
		    <?php echo $avatar ?>

                <div class="yajem_uname ">
                    <?php echo $userName ?> <br/>
                    <?php
                        $timestamp = new DateTime($item->timestamp);
                        echo $timestamp->format('d.m.Y H:i')
                    ?>
                </div>

        </div>
    </div>
    <div class="yajem_output">
        <?php echo nl2br($item->comment); ?>
    </div>

    <div class="yajem_full_grid_row"><div class="yajem_line"></div></div>

    <?php endforeach; ?>
</div>

<form action="<?php echo JRoute::_('index.php?option=com_yajem&view=events'); ?>"  name="commentForm" id="commentForm" method="post">

    <div class="yajem_flex_row yajem-button-group">
        <textarea form="commentForm" id="comment" wrap="soft" name="comment"><?php echo JText::_('COM_YAJEM_COMMENT_AREA') ?></textarea>
        <label id="yajem_comment" class="yajem_css_switch yajem_rounded" for="commit_comment"><?php echo JText::_('COM_YAJEM_SUBMIT_COMMENT') ?></label>
    </div>

    <input type="radio" class="yajem_hidden" id="commit_comment" name="commit_comment" value="commit" onchange="commentForm.submit()" />
    <input type="hidden" name="userId" value="<?php echo $userId; ?>" />
    <input type="hidden" name="eventId" value="<?php echo $this->event->id; ?>" />
    <input type="hidden" name="task" value="event.saveComment" />
    <?php echo JHtml::_( 'form.token' ); ?>
</form>
