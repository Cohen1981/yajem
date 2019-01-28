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
	 * @param null $pk
	 *
	 * @return bool
	 *
	 * @since 1.1
	 */
	public function delete($pk = null)
	{
		$this->deleteFile($pk);
		return parent::delete($pk);
	}

	/**
	 * @param $pk
	 *
	 * @return bool
	 *
	 * @since 1.1
	 */
	private function deleteFile($pk) {
		// get the file to delete
		$query = $this->getDbo()->getQuery(true);
		$query->select('file')->from('#__yajem_attachments')->where('id = ' . $pk);
		$file = $this->getDbo()->setQuery($query)->loadResult();
		//delete the file
		return File::delete(JPATH_SITE . DIRECTORY_SEPARATOR . $file);
	}
}