<?php
/**
 * @package     Joomla\Component\Yajem\Administrator\Classes
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomla\Component\Yajem\Administrator\Classes;

use Joomla\CMS\Factory;
use Joomla\CMS\User\UserHelper;
use Joomla\Component\Yajem\Administrator\Helpers\YajemParams;
use JModelLegacy;

/**
 * @package     Joomla\Component\Yajem\Administrator\Classes
 *
 * @since       1.2.0
 */
class YajemUserProfile
{
	/**
	 * @var int|null
	 * @since 1.2.0
	 */
	public $id = null;

	/**
	 * @var string|null
	 * @since 1.2.0
	 */
	public $name = null;

	/**
	 * @var string|null
	 * @since 1.2.0
	 */
	public $username = null;

	/**
	 * @var string|null
	 * @since 1.2.0
	 */
	public $email = null;

	/**
	 * @var string|null
	 * @since 1.2.0
	 */
	public $avatar = null;

	/**
	 * Array of attended Events. Key is eventID
	 * @var array
	 * @since 1.2.0
	 */
	public $attendants = array();

	/**
	 * YajemUserProfile constructor.
	 *
	 * @param   int $userId User id
	 *
	 * @since 1.2.0
	 */
	public function __construct($userId)
	{
		$profileKeys = array("id", "name", "username", "email", "avatar");

		$user = (array) Factory::getUser($userId);

		foreach ($user as $k => $v)
		{
			if (in_array($k, $profileKeys, true))
			{
				$this->$k = $user[$k] = $v;
			}
		}

		$userProfile = (array) UserHelper::getProfile($userId);

		foreach ($userProfile['profile'] as $k => $v)
		{
			if (in_array($k, $profileKeys, true))
			{
				$this->$k = $userProfile['profile'][$k] = $v;
			}
		}

		foreach ($userProfile['profileYajem'] as $k => $v)
		{
			if (in_array($k, $profileKeys, true))
			{
				$this->$k = $userProfile['profileYajem'][$k] = $v;
			}
		}

		if (!$this->name)
		{
			$this->name = $this->username;
		}

		if (!$this->avatar)
		{
			$this->avatar = "/media/com_yajem/images/user-image-blanco.png";
		}

		$modelAttendees = JModelLegacy::getInstance('Attendees', 'YajemModel');
		$events = $modelAttendees->getAllEventsForUser($this->id);

		foreach ($events as $event)
		{
			$this->attendants[$event->eventId] = $event;
		}
	}

}
