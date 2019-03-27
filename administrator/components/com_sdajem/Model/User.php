<?php
/**
 * @package     Sda\Jem\Admin\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Admin\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use Joomla\CMS\Component\ComponentHelper;
use Sda\Profiles\Admin\Model\Profile;

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\User
 *
 * Fields:
 *
 * @property  int       $id
 * @property  string    $name
 * @property  string    $username
 * @property  string    $email
 *
 * Relations:
 *
 * @property  Profile   $profile
 */
class User extends DataModel
{
	/**
	 * User constructor.
	 *
	 * @param   Container $container    The Container
	 * @param   array     $config       The Configuration
	 *
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		$config['tableName'] = '#__users';
		$config['idFieldName'] = 'id';
		parent::__construct($container, $config);

		$this->hasMany('attendees', 'Attendee', 'id', 'users_user_id');

		if (ComponentHelper::isEnabled('com_sdaprofiles'))
		{
			$this->hasOne('profile', 'Profile@com_sdaprofiles', 'id', 'users_user_id');
		}

	}
}