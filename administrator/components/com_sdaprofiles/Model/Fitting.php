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
 *
 * Model Sda\Profiles\Admin\Model\Fitting
 *
 * Fields:
 *
 * @property  int       $sdaprofiles_fitting_id
 * @property  int       $sdaprofiles_profile_id
 * @property  string    $type
 * @property  string    $detail
 * @property  int       $length
 * @property  int       $width
 *
 * Filters:
 *
 * @method  $this  sdaprofiles_fitting_id() sdaprofiles_profile_id(int $v)
 * @method  $this  enabled()                enabled(bool $v)
 * @method  $this  created_on()             created_on(string $v)
 * @method  $this  created_by()             created_by(int $v)
 * @method  $this  modified_on()            modified_on(string $v)
 * @method  $this  modified_by()            modified_by(int $v)
 * @method  $this  locked_on()              locked_on(string $v)
 * @method  $this  locked_by()              locked_by(int $v)
 *
 * Relations:
 *
 * @property  Profile $profile
 */
class Fitting extends DataModel
{
	/**
	 * Fitting constructor.
	 *
	 * @param   Container $container    The Container
	 * @param   array     $config       The Configuration
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
		$this->addBehaviour('Filters');
		$this->belongsTo('profile', 'Profile');
	}
}