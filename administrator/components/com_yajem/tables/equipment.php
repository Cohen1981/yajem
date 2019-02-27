<?php
/**
 * @package    yajem
 *
 * @author     abahl <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\Table\Table;
use Joomla\CMS\Factory;
use Yajem\Table\TableHelper;
use Joomla\Filesystem\Folder;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

/**
 * Yajem table.
 *
 * @since       1.0
 */
class YajemTableEquipment extends Table
{
	/**
	 * @var		int|null
	 * @since	version
	 */
	public $id = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $userId = null;

	/**
	 * @var		string|null
	 * @since	version
	 */
	public $type = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $length = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $width = null;

	/**
	 * YajemTableEvent constructor.
	 *
	 * @param   JDatabaseDriver $db DB Driver
	 *
	 * @since   1.0
	 */
	public function __construct($db)
	{
		parent::__construct('#__yajem_equipment', 'id', $db);
	}
}