<?php
/**
 * @package     Sda\Jem\Admin\Model
 * @subpackage
 *
 * @copyright   Alexander Bahlo
 * @license     GPL2
*/

namespace Sda\Jem\Admin\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use FOF30\Date\Date;

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\Location
 *
 * Fields:
 *
 * @property   int			$sdajem_location_id
 * @property   int			$sdajem_categorie_id
 * @property   string		$title
 * @property   string		$slug
 * @property   string		$description
 * @property   string		$url
 * @property   string		$street
 * @property   string		$postalCode
 * @property   string		$city
 * @property   string		$stateAddress
 * @property   string		$country
 * @property   string		$latlng
 * @property   int			$contactId
 * @property   string		$image
 * @property   int			$access
 * @property   int			$enabled
 * @property   int			$locked_by
 * @property   Date			$locked_on
 * @property   int			$hits
 * @property   int			$ordering
 * @property   Date			$created_on
 * @property   int			$created_by
 * @property   Date			$modified_on
 * @property   int			$modified_by
 *
 * Relations:
 *
 * @property  Contact       $contact
 * @property  Category      $category
 */
class Location extends DataModel
{
	/**
	 * Location constructor.
	 *
	 * @param   Container $container The Container
	 * @param   array     $config    The Configuration
	 *
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
		$this->hasOne('category', 'Category');
		$this->hasOne('contact', 'Contact', 'contactId', 'id');
		$this->belongsTo('event', 'Event');
	}
}
