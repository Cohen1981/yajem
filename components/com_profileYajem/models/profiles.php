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
use Joomla\CMS\Access\Access;
use Joomla\Component\Yajem\Administrator\Classes\YajemUserProfile;

/**
 * @package     Yajem
 *
 * @since       version
 */
class ProfileYajemModelProfiles extends ListModel
{
	/**
	 * YajemModelEvents constructor.
	 *
	 * @param   array $config Configuration array
	 *
	 * @since 1.0
	 */
	public function __construct($config = array())
	{
		$config['filter_fields'] = array();
		parent::__construct($config);
	}

	/**
	 * @param   null $ordering  column to order
	 * @param   null $direction direction
	 *
	 * @return void
	 *
	 * @since 1.0
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
	 * @since 1.0
	 */
	protected function getListQuery()
	{

		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('a.*	');
		$query->from('`#__yajem_events` AS a');

		$user = Factory::getUser();
		$viewLevel = implode(',', Access::getAuthorisedViewLevels($user->id));
		$query->where('a.access IN (' . $viewLevel . ')');

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
	 * @since 1.0
	 */
	public function getItems()
	{
		$items = parent::getItems();

		return $items;
	}
}
