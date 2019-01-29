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
	 * @var		int|null
	 * @since	version
	 */
	public $eventId = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $comment = null;

	/**
	 * @var		DateTime|null
	 * @since	version
	 */
	public $timestamp = null;

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

	/**
	 * @param   boolean $updateNulls Update nulls or not
	 *
	 * @return boolean
	 *
	 * @since 1.0
	 */
	public function store($updateNulls = false)
	{
		return parent::store($updateNulls);
	}
}