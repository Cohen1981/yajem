<?php
/**
 * @package     Sda\Jem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Site\Model;

use Sda\Jem\Admin\Model\Comment as AdminComment;
use FOF30\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

/**
 * @package     Sda\Jem\Site\Model
 *
 * @since       0.0.1
 */
class Comment extends AdminComment
{
	/**
	 *
	 * @return string Ready to show Html for the a Comment line
	 *
	 * @since 0.0.1
	 */
	public function getCommentHtml() : string
	{
		$html = "<div id=\"sdajem_comment_" . $this->sdajem_comment_id . "\" class=\"sdajem_comment_row\">";
		$html = $html . "<div class=\"sdajem_comment_user\">";
		$timestamp = new Date($this->timestamp);

		if ($this->user->profile)
		{
			$html = $html .
				"<div class='sdajem_avatar_container'>" .
				"<img class=\"sdajem_avatar\" src=\"" . $this->user->profile->avatar . "\"/>" .
				"</div>" .
				"<div class='sdajem_profile_details'>" .
				$this->user->profile->userName . "<br/>" . $timestamp->format('d.m.Y H:i') .
				"</div>";
		}
		else
		{
			$html = $html .
				"<div class='sdajem_profile_details'>" .
				$this->user->username . "<br/>" . $timestamp->format('d.m.Y H:i') .
				"</div>";
		}

		$html = $html . "</div>";
		$html = $html . "<div class=\"sdajem_comment_text\">" . nl2br($this->comment) . "</div>";
		$html = $html . "<div>";

		if ($this->users_user_id == Factory::getUser()->id)
		{
			$html = $html .
				//"<a href=\"" . Route::_('index.php?option=com_sdajem&task=deleteComment&id=' . $this->sdajem_comment_id) . "\">" .
				"<button type=\"button\" onclick=\"deleteCommentAjax(" . $this->sdajem_comment_id . ")\">" .
				"<i class=\"fas fa-trash\" aria-hidden=\"true\"></i>".
				"</button>";
				//"</a>";
		}

		$html = $html . "</div>";
		$html = $html . "</div>";

		return $html;
	}
}