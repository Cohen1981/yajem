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

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\Categorie
 *
 * Fields:
 *
 * @property   int			$sdajem_category_id
 * @property   string		$title
 * @property   string		$type
 *
 * Filters:
 *
 * @method  $this  type() type(string $v)
 */
class Category extends DataModel
{
	/**
	 * @var array
	 * @since 0.0.1
	 */
	protected $fillable = array('title', 'type');

	/**
	 * Category constructor.
	 *
	 * @param   Container $container    The Container
	 * @param   array     $config       The Configuration
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
		$this->addBehaviour('Filters');
	}
}
