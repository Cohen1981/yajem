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
use Joomla\CMS\Factory;

/**
 * @package     Yajem
 *
 * @since       1.1
 */
class YajemTableAttachment extends Table
{
	/**
	 * YajemTableAttachment constructor.
	 *
	 * @param   JDatabaseDriver $db DB Driver
	 *
	 * @since   1.1
	 */
	public function __construct($db)
	{
		parent::__construct('#__yajem_attachments', 'id', $db);
	}

	public function store($updateNulls = false)
	{
		return parent::store($updateNulls);
	}
}