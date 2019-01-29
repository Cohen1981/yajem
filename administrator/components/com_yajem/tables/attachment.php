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
use Joomla\Filesystem\File;
use Joomla\CMS\Factory;

/**
 * @package     Yajem
 *
 * @since       1.1
 */
class YajemTableAttachment extends Table
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
	public $eventId = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $locationId = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $title = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $description = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $file = null;

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

	/**
	 * @param   null $pk PK
	 *
	 * @return boolean
	 *
	 * @since 1.1
	 */
	public function delete($pk = null)
	{
		$this->deleteFile($pk);

		return parent::delete($pk);
	}

	/**
	 * @param   int $pk PK
	 *
	 * @return boolean
	 *
	 * @since 1.1
	 */
	private function deleteFile($pk)
	{
		// Get the file to delete
		$query = $this->getDbo()->getQuery(true);
		$query->select('file')->from('#__yajem_attachments')->where('id = ' . $pk);
		$file = $this->getDbo()->setQuery($query)->loadResult();

		// Delete the file
		return File::delete(JPATH_SITE . DIRECTORY_SEPARATOR . $file);
	}
}