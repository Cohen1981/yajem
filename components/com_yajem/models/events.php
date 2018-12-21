<?php
/**
 * @package     Yajem.Site
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
class YajemModelEvents extends ListModel
{
	/**
	 * YajemModelEvents constructor.
	 *
	 * @param   array $config Configuration array
	 *
	 * @since version
	 */
	public function __construct($config = array())
	{
		$config['filter_fields'] = array(
			'a.startDate',
			'a.endDate',
			'a.title',
			'location',
			'cat_title',
			'attendees'
		);
		parent::__construct($config);
	}

	/**
	 * @param   null $ordering  column to order
	 * @param   null $direction direction
	 *
	 * @return void
	 *
	 * @since version
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = Factory::getApplication('site');

		$this->setState('filter.search', $app->getUserStateFromRequest($this->context . '.filter.search', 'filter.search'));
		$this->setState('filter.catid', $app->getUserStateFromRequest($this->context . '.filter.catid', 'filter_catid'));

		$params = JComponentHelper::getParams('com_yajem');
		$this->setState('params', $params);

		$list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array');

		$app->setUserState($this->context . '.list', $list);
		$app->input->set('list', null);
		$ordering = $app->input->get('filter_order');

		if (!empty($ordering))
		{
			$list             = $app->getUserState($this->context . '.list');
			$list['ordering'] = $app->input->get('filter_order');
			$app->setUserState($this->context . '.list', $list);
		}

		$orderingDirection = $app->input->get('filter_order_Dir');

		if (!empty($orderingDirection))
		{
			$list              = $app->getUserState($this->context . '.list');
			$list['direction'] = $app->input->get('filter_order_Dir');
			$app->setUserState($this->context . '.list', $list);
		}

		$list = $app->getUserState($this->context . '.list');

		if (empty($list['ordering']))
		{
			$list['ordering'] = 'a.startDate';
		}

		if (empty($list['direction']))
		{
			$list['direction'] = 'asc';
		}

		if (isset($list['ordering']))
		{
			$this->setState('list.ordering', $list['ordering']);
		}

		if (isset($list['direction']))
		{
			$this->setState('list.direction', $list['direction']);
		}

		parent::populateState($list['ordering'], $list['direction']);
	}

	/**
	 *
	 * @return JDatabaseQuery
	 *
	 * @since version
	 */
	protected function getListQuery()
	{

		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$subquery = $db->getQuery(true);

		// Subquery to get the number of attending users
		$subquery->select('att.eventId, count(userId) AS attendees');
		$subquery->from('`#__yajem_attendees` AS att');
		$subquery->where('att.status=1');
		$subquery->group($db->quoteName('att.eventId'));

		// Get all Events
		$query->select('a.*	');
		$query->from('`#__yajem_events` AS a');

		// Join for lacations title
		$query->select('loc.title AS location');
		$query->join('LEFT', '#__yajem_locations AS loc ON loc.id = a.locationId');

		$query->select('attendees');
		$query->join('LEFT', '(' . $subquery . ') AS reg on reg.eventId = a.id');

		// Get the Category
		$query->select('c.title AS cat_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// only published events
		$query->where('a.published = 1');

		$query->order($db->escape($this->getState('list.ordering', 'a.startDate')) . ' ' .
			$db->escape($this->getState('list.direction', 'ASC'))
		);
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

		return $query;
	}

	/**
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	public function getItems()
	{
		$items = parent::getItems();

		return $items;
	}
}
