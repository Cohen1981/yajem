<?php
/**
 * @package     Joomla\Component\Yajem\Administrator\Classes
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Yajem\User;

use Joomla\CMS\Factory;
use Joomla\CMS\Access\Access;
use Joomla\CMS\User\UserHelper;
use Joomla\CMS\Uri\Uri;
use Yajem\Models\Attendees;

/**
 * @package     Joomla\Component\Yajem\Administrator\Classes
 *
 * @since       1.0.0
 */
class YajemUserProfile extends \stdClass
{
	/**
	 * @var int|null
	 * @since 1.0.0
	 */
	public $id = null;

	/**
	 * @var string|null
	 * @since 1.0.0
	 */
	public $name = null;

	/**
	 * @var string|null
	 * @since 1.0.0
	 */
	public $username = null;

	/**
	 * @var string|null
	 * @since 1.0.0
	 */
	public $email = null;

	/**
	 * @var string|null
	 * @since 1.0.0
	 */
	public $phone = null;

	/**
	 * @var string|null
	 * @since 1.0.0
	 */
	public $mobil = null;

	/**
	 * @var string|null
	 * @since 1.0.0
	 */
	public $address = null;

	/**
	 * @var string|null
	 * @since 1.0.0
	 */
	public $plzCity = null;

	/**
	 * @var string|null
	 * @since 1.0.0
	 */
	public $avatar = null;

	/**
	 * @var array|null
	 * @since 1.0.0
	 */
	public $accessLevels = null;

	/**
	 * YajemUserProfile constructor.
	 *
	 * @param   int $userId User id
	 *
	 * @since 1.0.0
	 */
	public function __construct($userId)
	{
		$profileKeys = array("id", "name", "username", "email", "avatar", "address1", "address2", "postal_code",
							"city", "phone", "mobil");

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

		$this->address = $this->address1 . " " . $this->address2;
		$this->plzCity = $this->postal_code . " " . $this->city;

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
	 * @since 1.0.0
	 */
	public function getAttendants(): array
	{
		$modelAttendees = new Attendees;
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
	 * @since 1.0.0
	 */
	public function getEventAttendingStatus($eventId): int
	{
		$attendants = $this->getAttendants();

		return $attendants[$eventId]->status;
	}

	/**
	 * Cast to YajemUserProfile
	 *
	 * @param   YajemUserProfile $object The Object to cast
	 *
	 * @return  YajemUserProfile
	 *
	 * @since 1.0.0
	 */
	static public function cast(YajemUserProfile $object): YajemUserProfile
	{
		return $object;
	}

	/**
	 * @param   string $format Date compatible format
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function getFormatedBirthDate(string $format = 'd.m.Y') : string
	{
		return date($format, strtotime($this->dob));
	}

}
