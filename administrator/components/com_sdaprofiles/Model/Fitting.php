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

/**
 * @package     Sda\Profiles\Model
 *
 * @since       0.0.1
 */
class Fitting extends DataModel
{
	public $fittingId;
	public $sdaprofilesProfileId;
	public $type;
	public $detail;
	public $length;
	public $width;
	/**
	 * Fitting constructor.
	 *
	 * @param   Container $container    The container
	 * @param   array     $config       The Config array
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		$defaultConfig = array(
			'tableName'   => '#__sdaprofiles_fittings',
			'idFieldName' => 'fittingId',
		);

		if (!is_array($config) || empty($config))
		{
			$config = array();
		}

		$config = array_merge($defaultConfig, $config);

		parent::__construct($container, $config);

		$this->belongsTo('profile',
			'Profile',
			'sdaprofilesProfileId',
			'profileId'
		);
	}

}
