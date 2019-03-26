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
use Joomla\CMS\Language\Text;

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\Attendee
 *
 * Fields:
 *
 * @property   int			$sdajem_attendee_id
 * @property   int			$sdajem_event_id
 * @property   int			$users_user_id
 * @property   int			$status
 *
 * Relations:
 *
 * @property  User          $user
 * @property  Event         $event
 */
class Attendee extends DataModel
{
	/**
	 * @var array
	 * @since 0.0.1
	 */
	protected $fillable = array('status');

	/**
	 * Attendee constructor.
	 *
	 * @param   Container $container The Container
	 * @param   array     $config    The Configuration
	 *
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
		$this->belongsTo('event', 'Event');
		$this->hasOne('user', 'User', 'users_user_id', 'id');
	}

	/**
	 *
	 * @return string Ready to show Html for the attendee
	 *
	 * @since 0.0.1
	 */
	public function getAttendingHtml() : string
	{
		switch ($this->status)
		{
			case 0:
				$status = "<div class=\"sdajem_status_label sdajem_grey\">" . Text::_('COM_SDAJEM_UNDECIDED') . "</div>";
				break;
			case 1:
				$status = "<div class=\"sdajem_status_label sdajem_green\">" . Text::_('COM_SDAJEM_ATTENDING') . "</div>";
				break;
			case 2:
				$status = "<div class=\"sdajem_status_label sdajem_red\">" . Text::_('COM_SDAJEM_NATTENDING') . "</div>";
				break;
		}

		$html = '';

		if ($this->user->profile)
		{
			$html = $html .
				"<div id=\"attendee" . $this->sdajem_attendee_id . "\" class=\"sdajem_profile_container\">" .
				"<div class='sdajem_avatar_container'>" .
				"<img class=\"sdajem_avatar\" src=\"" . $this->user->profile->avatar . "\"/>" .
				"</div>" .
				"<div class='sdajem_profile_details'>" .
				$this->user->profile->userName . "<br/>" . $status .
				"</div>" .
				"</div>";
		}
		else
		{
			$html = $html .
				"<div id=\"attendee" . $this->sdajem_attendee_id . "\" class=\"sdajem_profile_container\">" .
				"<div class='sdajem_profile_details'>" .
				$this->user->username . "<br/>" . $status .
				"</div>" .
				"</div>";
		}

		return $html;
	}
}
