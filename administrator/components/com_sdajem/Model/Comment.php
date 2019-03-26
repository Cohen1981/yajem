<?php
/**
 * @package     Sda\Jem\Admin\Model
 * @subpackage
 *
 * @copyright   Alexander Bahlo
 * @license     GPL2
*/

namespace Sda\Jem\Admin\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use FOF30\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\Comment
 *
 * Fields:
 *
 * @property   int			$sdajem_comment_id
 * @property   int			$users_user_id
 * @property   int			$sdajem_event_id
 * @property   string		$comment
 * @property   Date			$timestamp
 *
 * Relations:
 *
 * @property  User          $user
 */
class Comment extends DataModel
{
	/**
	 * Comment constructor.
	 *
	 * @param   Container $container The Container
	 * @param   array     $config    The Configuration
	 *
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
		$this->hasOne('user', 'User', 'users_user_id', 'id');
		$this->belongsTo('event', 'Event');
		$this->orderBy('timestamp', 'DESC');
	}

	/**
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeSave()
	{
		$this->timestamp = $this->timestamp->toSql(false, $this->getDbo());
	}

	/**
	 *
	 * @return string Ready to show Html for the a Comment line
	 *
	 * @since 0.0.1
	 */
	public function getCommentHtml() : string
	{
		$html = "<div class=\"sdajem_comment_row\">";
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
				"<a href=\"" . Route::_('index.php?option=com_sdajem&task=deleteComment&id=' . $this->sdajem_comment_id) . "\">" .
				"<i class=\"fas fa-trash\" aria-hidden=\"true\"></i></a>";
		}

		$html = $html . "</div>";
		$html = $html . "</div>";

		return $html;
	}
}
