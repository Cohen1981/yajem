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
use Sda\Model\SdaProtoModel;

/**
 * @package     Sda\Profiles\Admin\Model
 *
 * @since       0.1.3
 *
 * Model Sda\Profiles\Admin\Model\FittingType
 *
 * Fields:
 *
 * @property  int       $sdaprofiles_fitting_type_id
 * @property  string    $title
 * @property  int       $needSpace
 */
class FittingType extends SdaProtoModel
{
	/**
	 * FittingType constructor.
	 *
	 * @param   Container $container    The Container
	 * @param   array     $config       The Configuration
	 *
	 * @since 0.1.3
	 */
	public function __construct(Container $container, array $config = array())
	{
		$config['tableName'] = '#__sdaprofiles_fitting_types';
		$config['idFieldName'] = 'sdaprofiles_fitting_type_id';
		parent::__construct($container, $config);
		$this->belongsTo('fitting', 'Fitting', 'sdaprofiles_fitting_type_id', 'type');
		$this->belongsTo('fittingImage', 'FittingImage', 'sdaprofiles_fitting_type_id', 'type');
	}
}