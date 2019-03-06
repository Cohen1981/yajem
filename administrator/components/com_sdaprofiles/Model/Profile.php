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
use FOF30\Model\DataModel;

/**
 * @package     Sda\Profiles\Admin\Model
 *
 * @since       0.0.1
 */
class Profile extends DataModel
{
	public $sdaprofiles_profile_id;
	public $users_user_id;
	public $avatar;
	public $address1;
	public $address2;
	public $city;
	/**
	 * Profile constructor.
	 *
	 * @param   Container $container    Container
	 * @param   array     $config       Configuration Array
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		$this->hasOne('user', 'profile@com_users', 'users_user_id', 'id');
		$this->hasMany('fittings', 'Profile', 'sdaprofiles_profile_id', 'sdaprofiles_profile_id');
	}

}
