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
use FOF30\Date\Date;
use FOF30\Model\DataModel;
use Joomla\CMS\Language\Text;
use Sda\Jem\Admin\Helper\DateHelper;

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

	/**
	 * @return Date
	 */
	public function getModifiedOn(): Date
	{
		return DateHelper::getDateValue($this->modified_on);
	}

	public function getLastModified():Date
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('max(modified_on)')
			->from('#__sdajem_categories');
		$db->setQuery($query);
		$lastModified = $db->loadResult();

		if ($lastModified == null || $lastModified == "0000-00-00")
		{
			return null;
		}

		// Make sure it's not a Date already
		if (is_object($lastModified) && ($lastModified instanceof Date))
		{
			return $lastModified;
		}

		// Return the data transformed to a Date object
		return new Date($lastModified);
	}
}
