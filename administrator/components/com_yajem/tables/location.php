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
 * @since       version
 */
class YajemTableLocation extends Table
{
	/**
	 * YajemTableLocation constructor.
	 *
	 * @param   JDatabaseDriver $db DB Driver
	 *
	 * @since   1.0
	 */
	public function __construct($db)
	{
		parent::__construct('#__yajem_locations', 'id', $db);
	}

	/**
	 * Overloaded store method for locations table
	 *
	 * @param   boolean $updateNulls   default null
	 *
	 * @return   boolean               true on success, else false
	 *
	 * @since   version             1.0
	 */
	public function store($updateNulls = false)
	{
		$date = Factory::getDate()->toSql();
		$user = Factory::getUser()->get('id');

		$this->modified = $date;
		$this->modified_by = $user;

		if (empty($this->id))
		{
			// New record
			$this->created = $date;
			$this->created_by = $user;
		}

		// Generating the alias
		if (empty($this->alias))
		{
			$this->alias = $this->title;
			$this->alias = JApplicationHelper::stringURLSafe($this->alias, $this->language);

			if (trim(str_replace('-', '', $this->alias)) == '')
			{
				$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
			}
		}

		return parent::store($updateNulls);
	}

	/**
	 * Overloaded bind function
	 *
	 * @param   array|object $src       Src
	 * @param   array        $ignore    Params
	 *
	 * @return boolean
	 *
	 * @since version
	 */
	public function bind($src, $ignore = array())
	{
		if (isset($src['params']) && is_array($src['params']))
		{
			// Convert the params array to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($src['params']);
			$src['image'] = (string) $parameter;
		}

		return parent::bind($src, $ignore);
	}

	/**
	 * Overloaded delete function for enforcing data integrity
	 * Should also work when called from Frontend
	 *
	 * @param null $pk
	 *
	 * @return bool
	 *
	 * @since 1.0
	 */
	public function delete($pk = null)
	{
		$return = parent::delete($pk);

		if ($return)
		{
			// Now check for events to delete
			$db = $this->getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('id'))
				->from(
					$db->quoteName('#__yajem_events')
				)
				->where('locationId = ' . (int) $pk);
			$db->setQuery($query);
			$ids=$db->loadColumn();

			// deleting should be done through native table which should care for own dependencies.
			jimport('joomla.application.component.table');
			JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_yajem' . DS . 'tables');
			$eventTable = JTable::getInstance( 'Event', 'YajemTable' );
			foreach ($ids as $id)
			{
				$eventTable->delete($id);
			}
		}

		return $return;
	}
}