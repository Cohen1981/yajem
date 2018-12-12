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
class YajemModelAttendees extends ListModel
{
	/**
	 * YajemModelAttendees constructor.
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
				'eventId','a.eventId',
				'userId','a.userId',
				'status','a.status',
				'comment','a.comment',
				'u.id','u.name',
				'event',
				'attendee'
			);
		}

		parent::__construct($config);
	}

	/**
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	public function getAllUsersForEvent()
	{
		$db = $this->getDbo();
		$db->setQuery($this->getListQuery());

		return $db->loadObjectList();
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
		$this->setState('filter.eventId', $app->getUserStateFromRequest($this->context . '.filter.eventId', 'filter_eventId'));
		$this->setState('filter.userId', $app->getUserStateFromRequest($this->context . '.filter.userId', 'filter_userId'));

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

		$query->select($db->quoteName(array('a.id', 'a.eventId', 'a.userId', 'a.status', 'a.comment')));

		$query->from('#__yajem_attendees AS a');

		$query->select('e.title AS event');
		$query->join('LEFT', '#__yajem_events AS e ON e.id = a.eventId');

		$query->select('u.name AS attendee');
		$query->join('LEFT', '#__users AS u ON u.id = a.userId');

		$eventId = $this->getState('filter.eventId');

		if (is_numeric($eventId)) // Set Filter for State
		{
			$query->where('a.eventId = ' . (int) $eventId);
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
				$query->where('( e.title LIKE ' . $search . ' )');
			}
		}

		$filterUser = $this->state->get('filter.userId');

		if ($filterUser)
		{
			$query->where("a.`userId` = '" . $db->escape($filterUser) . "'");
		}

		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * @param $eventId
	 *
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function deleteAttendeesForEvent($eventId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->delete($db->quoteName('#__yajem_attendees'));
		$query->where($db->quoteName('eventId') . ' = ' . (int) $eventId);

		$db->setQuery($query);

		return $db->execute();
	}
}
