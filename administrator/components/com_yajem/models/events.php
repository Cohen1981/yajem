<?php
/**
 * @package    yajem
 *
 * @author     abahl <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * Yajem
 *
 * @package  yajem
 * @since    1.0
 */
class YajemModelEvents extends ListModel
{
	/**
	 * YajemModelEvents constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @since   1.0
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
				'hoster','a.hoster',
				'organizer', 'org.name',
				'startDate', 'a.startDate',
				'endDate', 'a.endDate',
				'startDateTime', 'a.startDateTime',
				'endDateTime', 'a.endDateTime'
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
	 * @since 1.0
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
	 * Getting the list of Events
	 *
	 * @return JDatabaseQuery
	 *
	 * @since 1.0
	 */
	protected function getListQuery()
	{
		$params = JComponentHelper::getParams('com_yajem');

		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select(
			$db->quoteName(
				array('a.id', 'a.published', 'a.ordering', 'a.title',
					'a.image',	'a.startDateTime', 'a.endDateTime', 'a.endDate',
					'a.startDate','a.allDayEvent')
			)
		);

		$query->from('#__yajem_events AS a');

		$query->select('u.name AS created_by_name');
		$query->join('LEFT', '#__users AS u ON u.id = a.createdBy');

		$query->select('m.name AS modified_by_name');
		$query->join('LEFT', '#__users AS m ON m.id = a.modifiedBy');

		$query->select('c.title AS catid');
		$query->join('LEFT', '#__categories AS c ON c.id = a.`catid`');

		// If no hoster is used, we don't need to join.
		if ((bool) $params->get('use_host'))
		{
			$query->select('h.name AS hoster');
			$query->join('LEFT', '#__contact_details AS h ON h.id = a.hostId');
		}

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
