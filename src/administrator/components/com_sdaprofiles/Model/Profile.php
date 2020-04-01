<?php
/**
 * @package     Sda\Profiles\Admin\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Profiles\Admin\Model;

use FOF30\Container\Container;
use FOF30\Date\Date;
use Sda\Jem\Admin\Model\Attendee;
use Sda\Profiles\Admin\Model\User as UserAlias;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Sda\Model\SdaProtoModel;
use FOF30\Model\DataModel\Collection;

/**
 * @package     Sda\Profiles\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Profiles\Admin\Model\Profile
 *
 * Fields:
 *
 * @property  int           $sdaprofiles_profile_id
 * @property  int           $users_user_id
 * @property  int           $groupProfile
 * @property  int           $defaultGroup
 * @property  string        $userName
 * @property  string        $avatar
 * @property  string        $address1
 * @property  string        $address2
 * @property  string        $city
 * @property  int           $postal
 * @property  string        $phone
 * @property  string        $mobil
 * @property  string        $dob
 * @property  int           $mailOnNew
 * @property  int           $mailOnEdited
 *
 * Filters:
 *
 * @method  $this  sdaprofiles_profile_id() sdaprofiles_profile_id(int $v)
 * @method  $this  enabled()                enabled(bool $v)
 * @method  $this  token()                  token(string $v)
 * @method  $this  created_on()             created_on(string $v)
 * @method  $this  created_by()             created_by(int $v)
 * @method  $this  modified_on()            modified_on(string $v)
 * @method  $this  modified_by()            modified_by(int $v)
 * @method  $this  locked_on()              locked_on(string $v)
 * @method  $this  locked_by()              locked_by(int $v)
 *
 * Relations:
 *
 * @property  UserAlias                 $user
 * @property  Fitting                   $fittings
 * @property  Attendee                  $attendees  Only if sdajem is installed and active
 * @property  Collection                $organizing Only if sdajem is installed and active
 *
 */
class Profile extends SdaProtoModel
{
	/**
	 * Profile constructor.
	 *
	 * @param   Container $container The Container
	 * @param   array     $config    The configuration
	 *
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
		$this->addBehaviour('Filters');
		$this->hasOne('user', 'User', 'users_user_id', 'id');
		$this->hasMany('fittings', 'Fitting');

		if (ComponentHelper::isEnabled('com_sdaprofiles'))
		{
			$this->hasMany('attendees', 'Attendee@com_sdajem', 'users_user_id', 'users_user_id');
			$this->hasMany('organizing', 'Event@com_sdajem', 'users_user_id', 'organizerId');
		}
	}

	/**
	 * @param   string $value The date and time as string
	 *
	 * @return Date
	 *
	 * @since 0.0.1
	 */
	protected function getDobAttribute($value)
	{
		// Make sure it's not a Date already
		if (is_object($value) && ($value instanceof \FOF30\Date\Date))
		{
			return $value;
		}

		// Return the data transformed to a Date object
		return new Date($value);
	}

	/**
	 * @param   Date $value The date and time as Date
	 *
	 * @return string
	 *
	 * @since 0.0.1
	 */
	protected function setDobAttribute($value)
	{
		if ($value instanceof Date)
		{
			return $value->toSql();
		}

		return $value;
	}

	/**
	 * Enforcing data sanity
	 *
	 * @return void
	 *
	 * @since 0.1.1
	 */
	protected function onBeforeDelete()
	{
		if ($this->fittings)
		{
			/** @var Fitting $fitting */
			foreach ($this->fittings as $fitting)
			{
				$fitting->forceDelete();
			}
		}
	}

	/**
	 * @param   int|null $userId UserId, if null current logged in User is used.
	 *
	 * @return integer
	 *
	 * @since 0.1.2
	 */
	public static function getProfileIdForUserId(int $userId = null) : int
	{
		$userId = ($userId) ? $userId : Factory::getUser()->id;

		$dbo = Factory::getDbo();
		$query = $dbo->getQuery(true);
		$query->select('sdaprofiles_profile_id')
			->from('#__sdaprofiles_profiles')
			->where('users_user_id='.$userId);
		$dbo->setQuery($query);

		return $dbo->loadResult();
	}

	/**
	 * @return array
	 *
	 * @since 0.1.7
	 */
	public static function getGroupProfiles()
	{
		$dbo = Factory::getDbo();
		$query = $dbo->getQuery(true);
		$query->select('*')
			->from('#__sdaprofiles_profiles')
			->where('groupProfile=1');
		$dbo->setQuery($query);

		return $dbo->loadObjectList();
	}

	/**
	 * @return void
	 *
	 * @since 0.1.9
	 */
	public function onAfterSave()
	{
		if ((bool) $this->input->get('defaultGroup'))
		{
			$this->standardGroup();
		}
	}

	/**
	 * Set default = 0 for all other Groups.
	 *
	 * @return void
	 *
	 * @since 0.1.9
	 */
	private function standardGroup()
	{
		$dbo = Factory::getDbo();
		$query = $dbo->getQuery(true);
		$query->update('#__sdaprofiles_profiles')
			->set('defaultGroup=0')
			->where(array('sdaprofiles_profile_id !=' . $this->sdaprofiles_profile_id, 'users_user_id is NULL'));
		$dbo->setQuery($query);

		$dbo->execute();
	}

	/**
	 * @param   boolean $includeGroups include Group Profiles
	 *
	 * @return mixed
	 *
	 * @since 0.3.4
	 */
	public function getAllProfiles(bool $includeGroups)
	{
		$dbo = Factory::getDbo();
		$query = $dbo->getQuery(true);
		$query->select('*')
			->from('#__sdaprofiles_profiles');

		if (!$includeGroups)
		{
			$query->where('groupProfile=0');
		}

		$dbo->setQuery($query);

		return $dbo->loadObjectList();
	}

}