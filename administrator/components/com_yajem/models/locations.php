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

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemModelLocations extends ListModel
{
	/**
	 * YajemModelLocations constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @since version
	 */
	public function __construct(array $config = array())
	{
		if (empty($config['filter_fields']))
		{
			// Add the standard ordering filtering fields whitelist.
			// Note to self. Must be exactly written as in the default.php. 'a.`field`' not the same as 'a.field'
			$config['filter_fields'] = array(
				'id', 'a.id',
				'published', 'a.published',
				'ordering', 'a.ordering',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'title','a.title',
				'catid', 'a.catid',
				'image', 'a.image',
				'contact', 'con.contact'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   null $ordering  default=a.title
	 * @param   null $direction default=asc
	 *
	 * @return void
	 * @since version
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = Factory::getApplication('administrator');

		$this->setState('filter.search', $app->getUserStateFromRequest($this->context . '.filter.search', 'filter.search'));
		$this->setState('filter.published', $app->getUserStateFromRequest($this->context . '.filter.published', 'filter_published'));
		$this->setState('filter.catid', $app->getUserStateFromRequest($this->context . '.filter.catid', 'filter_catid'));

		$params = JComponentHelper::getParams('com_yajem');
		$this->setState('params', $params);

		parent::populateState('a.id', 'desc');
	}

	/**
	 *
	 * @return JDatabaseQuery
	 *
	 * @since version
	 */
	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('a.id', 'a.published', 'a.ordering', 'a.title', 'a.image')));

		$query->from('#__yajem_locations AS a');

		$query->select('u.name AS created_by_name');
		$query->join('LEFT', '#__users AS u ON u.id = a.created_by');

		$query->select('m.name AS modified_by_name');
		$query->join('LEFT', '#__users AS m ON m.id = a.modified_by');

		$query->select('c.title AS catid');
		$query->join('LEFT', '#__categories AS c ON c.id = a.`catid`');

		$query->select('con.name AS contact');
		$query->join('LEFT', '#__contact_details AS con ON con.id = a.contactid');

		$published = $this->getState('filter.published');

		if (is_numeric($published)) // Set Filter for State
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '') // No Filter for published, show all
		{
			$query->where('(a.published IN (0, 1, 2, 3))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.title LIKE ' . $search . ' )');
			}
		}

		$filterCatid = $this->state->get('filter.catid');

		if ($filterCatid)
		{
			$query->where("a.`catid` = '" . $db->escape($filterCatid) . "'");
		}

		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}
}
