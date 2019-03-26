<?php
/**
 * @package     Sda\Jem\Admin\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Admin\Model;

use FOF30\Model\DataModel;
use FOF30\Container\Container;

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\User
 *
 * Fields:
 *
 * @property  int       $id
 * @property  string    $name
 */
class Contact extends DataModel
{
	/**
	 * Contact constructor.
	 *
	 * @param   Container $container    The Container
	 * @param   array     $config       The Configuration
	 *
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		$config['tableName'] = '#__contact_details';
		$config['idFieldName'] = 'id';
		parent::__construct($container, $config);
	}
}