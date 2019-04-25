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
 * @property  string    $description
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

	/**
	 * Enforcing Data sanity
	 *
	 * @return void
	 *
	 * @since 0.1.7
	 */
	protected function onBeforeDelete()
	{
		/** @var Fitting $fittingModel */
		$fittingModel = $this->container->factory->model('Fitting');
		$fittings = $fittingModel->getItemsArray();

		/** @var Fitting $fitting */
		foreach ($fittings as $fitting)
		{
			if ((int) $fitting->sdaprofiles_fitting_image_id === (int) $this->sdaprofiles_fitting_image_id)
			{
				$fitting->forceDelete();
			}
		}
	}

	/**
	 * @param   string $mime Optional set the needed MIME-TYPE
	 *
	 * @return string
	 *
	 * @since 0.1.7
	 */
	public function getDataURI($mime = '')
	{
		// A few settings
		$image = $this->image;

		// Read image path, convert to base64 encoding
		$imageData = base64_encode(file_get_contents($image));

		// Format the image SRC:  data:{mime};base64,{data};
		$src = 'data: ' . mime_content_type($image) . ';base64,' . $imageData;

		return $src;
	}
}