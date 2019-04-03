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
use Joomla\CMS\Language\Text;

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\Category
 *
 * Fields:
 *
 * @property   int			$sdajem_category_id
 * @property   string		$title
 * @property   int		    $type
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

	public static function getCatType(array $input) : string
	{
		if ($input['fieldValue'] == 0)
		{
			return Text::_('COM_SDAJEM_TITLE_LOCATION_BASIC');
		}
		else
		{
			return Text::_('COM_SDAJEM_TITLE_EVENT_BASIC');
		}
	}
}
