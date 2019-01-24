<?php
/**
 * @package     Yajem.Administrator
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;

/**
 * @package     Yajem
 *
 * @since       1.0
 */
class YajemTableComment extends Table
{
	/**
	 * YajemTableAttendee constructor.
	 *
	 * @param   JDatabaseDriver $db DB Driver
	 *
	 * @since   1.0
	 */
	public function __construct($db)
	{
		parent::__construct('#__yajem_comments', 'id', $db);
	}

	public function store($updateNulls = false)
	{
		return parent::store($updateNulls);
	}
}