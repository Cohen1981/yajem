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
use Joomla\Registry\Registry;

?>
<div class="yajem_image">
	<?php
	$this->location->img = new Registry;
	$this->location->img->loadString($this->location->image);

	$caption = $this->location->img->get('caption') ? : '';
	$src = JURI::root() . ($this->location->img->get('image') ? : '' );
	?>

	<img src="<?php echo $src; ?>" title="<?php echo $caption; ?>" />
</div>

<div id="event_location" class="yajem_grid_section">

	<div class="yajem_label">
		<?php echo JText::_('COM_YAJEM_EVENT_STREET_LABEL'); ?>
	</div>
	<div class="yajem_output">
		<?php echo $this->location->street ?>
	</div>

	<div class="yajem_label">
		<?php echo JText::_('COM_YAJEM_EVENT_PLZCITY_LABEL'); ?>
	</div>
	<div class="yajem_output">
		<?php echo $this->location->postalCode ?> <?php echo $this->location->city ?>
	</div>

	<?php if ($this->location->description): ?>
		<div class="yajem_label">
			<?php echo JText::_('COM_YAJEM_LOCATION_DESCRIPTION'); ?>
		</div>
		<div class="yajem_output">
			<?php echo $this->location->description; ?>
		</div>
	<?php endif;?>
    <div class="yajem_label">
		<?php echo JText::_('COM_YAJEM_ATTACHMENTS_LABEL');?>
    </div>
    <div class="yajem_output">
		<?php foreach ($this->location->attachments as $a => $attachment) : ?>
            <div class="yajem_flex_row">
                <div>
					<?php
					$src = JURI::root() . $attachment->file;
					?>
                    <a href="<?php echo $src ?>" target="_blank"><i class="fas fa-paperclip" aria-hidden="true">&nbsp;</i><?php echo $attachment->title ?></a>
                </div>
            </div>
		<?php endforeach; ?>
    </div>
</div>
