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
use Joomla\CMS\Access\Access;
use Joomla\CMS\User\UserHelper;
use Joomla\Component\Yajem\Administrator\Helpers\YajemParams;
use Joomla\CMS\Uri\Uri;
use JModelLegacy;

/**
 * @package     Joomla\Component\Yajem\Administrator\Classes
 *
 * @since       1.2.0
 */
class YajemUserProfile extends \stdClass
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
	 * @var array|null
	 * @since 1.2.0
	 */
	public $accessLevels = null;

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
					$this->$k = $userProfile['profile'][$k] = $v;
				}
			}
		}

		if ($userProfile['profileYajem'])
		{
			foreach ($userProfile['profileYajem'] as $k => $v)
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
			$this->avatar = URI::root() . "media/com_yajem/images/user-image-blanco.png";
		}

		$this->accessLevels = Access::getAuthorisedViewLevels($this->id);
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

	/**
	 * @param   int $eventId The Event Id
	 *
	 * @return integer Status
	 *
	 * @since 1.2.1
	 */
	public function getEventAttendingStatus($eventId)
	{
		$attendants = $this->getAttendants();

		return $attendants[$eventId]->status;
	}

}
