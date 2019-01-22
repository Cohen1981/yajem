<?php
/**
 * @package     com_yajem\adminitsrator\helpers
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace com_yajem\administrator\helpers;

use Joomla\CMS\Table\Table;
use Joomla\CMS\Factory;

/**
 * Class holding functions for enforcing data sanity
 *
 * @package     com_yajem\administrator\helpers
 *
 * @since       1.1
 */
class tableHelper
{
	/**
	 * Checks for rows referencing the given id and deletes them
	 *
	 * @param string    $tableName          Name of the foreign table including '#__' example '#__yajem_attendees'
	 * @param string    $jTableInstanceType Name of the Type example 'Attendee'
	 * @param string    $foreignKeyName     Name of the foreign key in the foreign table
	 * @param int       $id                 id to search for
	 *
	 *
	 * @since 1.1
	 */
	public function deleteForeignTable($tableName, $jTableInstanceType, $foreignKeyName, $id)
	{
		jimport('joomla.application.component.table');

		// Get the pks of the foreign table to delete
		$db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('id'))
			->from(
				$db->quoteName($tableName)
			)
			->where($foreignKeyName . ' = ' . (int) $id);
		$db->setQuery($query);
		$ids = $db->loadColumn();

		if (count($ids)>0) {
			// deleting should be done through native table which should care for own dependencies.
			Table::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_yajem' . DS . 'tables');
			$foreignTable = Table::getInstance($jTableInstanceType, 'YajemTable');
			foreach ($ids as $pk)
			{
				$foreignTable->delete($pk);
			}
		}
	}
}