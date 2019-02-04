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

		if ($userProfile['profile'])
		{
			foreach ($userProfile['profile'] as $k => $v)
			{
				if (in_array($k, $profileKeys, true))
				{
					$profile[$k] = $userProfile['profile'][$k] = $v;
				}
			}
		}

		if ($userProfile['profileYajem'])
		{
			foreach ($userProfile['profileYajem'] as $k => $v)
			{
				$profile[$k] = $userProfile['profileYajem'][$k] = $v;
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
	}

	/**
	 * @return array
	 * @since 1.2.0
	 */
	public function getAttendants(): array
	{
		$modelAttendees = JModelLegacy::getInstance('Attendees', 'YajemModel');
		$events = $modelAttendees->getAllEventsForUser($this->id);

		foreach ($events as $event)
		{
			$attendants[$event->eventId] = $event;
		}

		return $attendants;
	}

}
