<?php
/**
 * @package     Sda\Contacts\Admin\Model
 * @subpackage
 *
 * @copyright   Alexander Bahlo
 * @license     GPL2
*/

namespace Sda\Contacts\Admin\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use FOF30\Date\Date;

/**
 * @package     Sda\Contacts\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Contacts\Admin\Model\Contact
 *
 * Fields:
 *
 * @property   int			    $sdacontacts_contact_id
 * @property   string			$title
 * @property   string			$slug
 * @property   string			$con_position
 * @property   string			$address
 * @property   string           $city
 * @property   string			$state
 * @property   string			$country
 * @property   string			$postcode
 * @property   string			$telephone
 * @property   string			$fax
 * @property   string			$misc
 * @property   string			$image
 * @property   string			$email
 * @property   int			    $catid
 * @property   int			    $access
 * @property   string			$mobile
 * @property   string			$webpage
 * @property   int			    $enabled
 * @property   int			    $created_by
 * @property   Date			    $created_on
 * @property   int			    $modified_by
 * @property   Date			    $modified_on
 * @property   int			    $locked_by
 * @property   Date			    $locked_on
 */
class Contact extends DataModel
{
	/**
	 * Contact constructor.
	 *
	 * @param   Container $container    The Container
	 * @param   array     $config       A Configuration
	 *
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		$config['behaviours'] = array('Filters', 'Access');
		parent::__construct($container, $config);
	}
}
