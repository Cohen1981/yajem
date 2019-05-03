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
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Sda\Profiles\Admin\Model\Profile;
use Sda\Profiles\Site\Model\Fitting;

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
 * @property   int          $sdaprofiles_profile_id
 * @property   int			$status
 * @property   array        $sdaprofilesFittingIds
 *
 * Relations:
 *
 * @property  User          $user
 * @property  Event         $event
 * @property  Profile       $profile Only if com_sdaprofiles installed
 */
class Attendee extends DataModel
{
	/**
	 * @var array
	 * @since 0.0.1
	 */
	protected $fillable = array('status', 'sdajem_event_id', 'users_user_id', 'sdaprofiles_profile_id');

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
		$config['behaviours'] = array('Filters');
		parent::__construct($container, $config);
		$this->belongsTo('event', 'Event');
		$this->hasOne('user', 'User', 'users_user_id', 'id');

		if (ComponentHelper::isEnabled('com_sdaprofiles'))
		{
			$this->hasOne('profile', 'Profile@com_sdaprofiles', 'sdaprofiles_profile_id', 'sdaprofiles_profile_id');
		}
	}

	protected function setSdaprofilesprofileidAttribute($value)
	{
		if ($value == '' && ComponentHelper::isEnabled('com_sdaprofiles'))
		{
			/** @var Profile $profile */
			$profile = Container::getInstance('com_sdaprofiles')->factory->model('Profile');
			$id = Profile::getProfileIdForUserId(Factory::getUser()->id);

			return $id;
		}
		else
		{
			return $value;
		}
	}

	/**
	 * @param   int $userId     User Id
	 * @param   int $eventId    Event Id
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function getAttendeeForEventAndUser(int $userId, int $eventId)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('sdajem_attendee_id')
			->from('#__sdajem_attendees')
			->where(array('sdajem_event_id=' . $eventId, 'users_user_id=' . $userId));
		$db->setQuery($query);
		$id = $db->loadResult();

		// Calling load with null value gets a random row. So we don't even call load when null.
		if ($id != null)
		{
			$this->load($id);
		}
	}

	/**
	 *
	 * @return integer
	 *
	 * @since 0.0.1
	 */
	public function getSpaceReuirementForEvent() : int
	{
		$spaceRequired = 0;

		if ($this->sdaprofilesFittingIds)
		{
			/** @var Fitting $fitting */
			$fitting = Container::getInstance('com_sdaprofiles')->factory->model('Fitting');

			foreach ($this->sdaprofilesFittingIds as $id)
			{
				$fitting->load($id);
				$spaceRequired = $spaceRequired + $fitting->getRequiredSpace();
			}
		}

		return $spaceRequired;
	}

	/**
	 * @param   array $value The date and time as Date
	 *
	 * @return string
	 *
	 * @since 0.0.1
	 */
	protected function setSdaprofilesFittingIdsAttribute($value)
	{
		return json_encode($value);
	}

	/**
	 * @param   string $value The date and time as string
	 *
	 * @return array | null
	 *
	 * @since 0.0.1
	 */
	protected function getSdaprofilesFittingIdsAttribute($value)
	{
		return json_decode($value);
	}
}
