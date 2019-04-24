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
 * @since       0.1.3
 *
 * Model Sda\Profiles\Admin\Model\FittingImage
 *
 * Fields:
 *
 * @property  int       $sdaprofiles_fitting_image_id
 * @property  string    $image
 *
 * Relations:
 *
 * @property  FittingType $type
 */
class FittingImage extends DataModel
{
	/**
	 * FittingImage constructor.
	 *
	 * @param   Container $container    The App Container
	 * @param   array     $config       A Configuration array
	 *
	 * @since 0.1.3
	 */
	public function __construct(Container $container, array $config = array())
	{
		$config['tableName'] = '#__sdaprofiles_fitting_images';
		$config['idFieldName'] = 'sdaprofiles_fitting_image_id';
		parent::__construct($container, $config);
		$this->addBehaviour('Filters');
		$this->belongsTo('fitting', 'Fitting', 'sdaprofiles_fitting_image_id', 'sdaprofiles_fitting_image_id');
		$this->hasOne('type', 'FittingType', 'type', 'sdaprofiles_fitting_type_id');
	}
}