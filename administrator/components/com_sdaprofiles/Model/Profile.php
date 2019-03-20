<?php
/**
 * @package     Sda\Profiles\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Profiles\Admin\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use DateTime;

/**
 * @package     Sda\Profiles\Model
 *
 * @since       0.0.1
 */
class Profile extends DataModel
{
	/**
	 * @var		int|null
	 * @since	0.0.1
	 */
	public $profileId = null;

	/**
	 * @var		int|null
	 * @since	0.0.1
	 */
	public $usersUserId = null;

	/**
	 * @var		String|null
	 * @since	0.0.1
	 */
	public $avatar = null;

	/**
	 * @var		String|null
	 * @since	0.0.1
	 */
	public $address1 = null;

	/**
	 * @var		String|null
	 * @since	0.0.1
	 */
	public $address2 = null;

	/**
	 * @var		String|null
	 * @since	0.0.1
	 */
	public $city = null;

	/**
	 * @var		int|null
	 * @since	0.0.1
	 */
	public $postal = null;

	/**
	 * @var		String|null
	 * @since	0.0.1
	 */
	public $phone = null;

	/**
	 * @var		String|null
	 * @since	0.0.1
	 */
	public $mobil = null;

	/**
	 * @var		DateTime|null
	 * @since	0.0.1
	 */
	public $dob = null;

	/**
	 * Profile constructor.
	 *
	 * @param   Container $container    The container
	 * @param   array     $config       The Config array
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		$defaultConfig = array(
			'tableName'   => '#__sdaprofiles_profiles',
			'idFieldName' => 'profileId',
		);

		if (!is_array($config) || empty($config))
		{
			$config = array();
		}

		$config = array_merge($defaultConfig, $config);

		parent::__construct($container, $config);

		$this->hasMany('fittings',
			'Fitting',
			'profileId',
			'sdaprofilesProfileId'
		);
	}

}
