<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Model;

\defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\QueryInterface;

class AttendingsModel extends ListModel
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'event_id', 'a.event_id',
				'eventTitle', 'e.title',
				'users_user_id', 'a.users_user_id',
				'attendeeName', 'at.username',
				'Status', 'a.Status',
				'startDateTime', 'e.startDateTime',
				'endDateTime', 'e.endDateTime'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  QueryInterface
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		$currentUser = Factory::getApplication()->getIdentity();
		// Create a new query object.
		$db = $this->getDatabase();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				[
					$db->quoteName('a.id'),
					$db->quoteName('a.event_id'),
					$db->quoteName('a.users_user_id'),
					$db->quoteName('a.status'),
				]
			)
		);
		$query->from($db->quoteName('#__sdajem_attendings', 'a'));

		// join event
		$query->select($db->quoteName('e.title', 'eventTitle'))
			->select($db->quoteName('e.startDateTime', 'startDateTime'))
			->select($db->quoteName('e.endDateTime', 'endDateTime'))
			->join(
				'LEFT',
				$db->quoteName('#__sdajem_events', 'e') . ' ON ' . $db->quoteName('e.id') . '=' . $db->quoteName('a.event_id')
			);

		//Join over User as attendee
		$query->select($db->quoteName('at.username', 'attendeeName'))
			->join(
				'LEFT',
				$db->quoteName('#__users', 'at') . ' ON ' . $db->quoteName('at.id') . ' = ' . $db->quoteName('a.users_user_id')
			);

		// Filter on user. Default Current User
		if ($this->getState('filter.users_user_id'))
		{
			if ($user = $this->getState('filter.users_user_id'))
			{
				$query->where($db->quoteName('a.users_user_id') . ' = ' . $db->quote($user));
			}
		} else {
			$query->where($db->quoteName('a.users_user_id') . ' = ' . $db->quote($currentUser->id));
		}

		// Filter on event
		if ($event = $this->getState('filter.event_id'))
		{
			$query->where($db->quoteName('a.event_id') . ' = ' . $db->quote($event));
		}

		// Filter by search in name.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where(
					'(' . $db->quoteName('e.title') . ' LIKE ' . $search . ')'
				);
				$query->extendWhere('OR', '(' . $db->quoteName('at.username') . ' LIKE ' . $search . ')');
			}
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'e.startDateTime');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * @param   int|null  $eventId
	 *
	 * @return mixed
	 *
	 * @since 1.0.8
	 */
	public function getAttendingsIdToEvent(int $eventId = null)
	{
		// Create a new query object.
		$db = $this->getDatabase();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select($db->quoteName('a.id'));

		$query->from($db->quoteName('#__sdajem_attendings', 'a'));

		$query->where($db->quoteName('a.event_id') . '= :eventId');

		$query->bind(':eventId', $eventId);

		$db->setQuery($query);
		$data = $db->loadColumn();

		return $data;
	}
}