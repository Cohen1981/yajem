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
class Fitting extends DataModel
{
	/**
	 * Fitting constructor.
	 *
	 * @param   Container $container    Container
	 * @param   array     $config       Configuration array
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		$this->belongsTo('profile', 'Profile', 'sdaprofiles_profile_id', 'sdaprofiles_profile_id');
	}
}