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
use FOF30\Model\DataModel;
use Sda\Profiles\Admin\Model\User as UserAlias;
use Sda\Profiles\Admin\Model\Fitting;

/**
 * @package     Sda\Profiles\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Profiles\Admin\Model\Profile
 *
 * Fields:
 *
 * @property  int       $sdaprofiles_profile_id
 * @property  int       $users_user_id
 * @property  string    $userName
 * @property  string    $avatar
 * @property  string    $address1
 * @property  string    $address2
 * @property  string    $city
 * @property  int       $postal
 * @property  string    $phone
 * @property  string    $mobil
 * @property  string    $dob
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
 * @property  UserAlias $user
 * @property  Fitting   $fittings
 *
 */
class Profile extends DataModel
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
	}
}