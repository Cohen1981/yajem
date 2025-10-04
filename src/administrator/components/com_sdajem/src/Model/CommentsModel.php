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

class CommentsModel extends ListModel
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'c.id',
				'sdajem_event_id', 'c.event_id',
				'eventTitle', 'e.title',
				'users_user_id', 'c.users_user_id',
				'userName', 'at.username',
				'comment', 'c.comment',
				'timestamp', 'c.timestamp',
				'commentReadBy', 'c.commentReadBy'
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
					$db->quoteName('c.id'),
					$db->quoteName('c.sdajem_event_id'),
					$db->quoteName('c.users_user_id'),
					$db->quoteName('c.comment'),
					$db->quoteName('c.timestamp'),
					$db->quoteName('c.commentReadBy'),
				]
			)
		);
		$query->from($db->quoteName('#__sdajem_comments', 'c'));

		// join event
		$query->select($db->quoteName('e.title', 'eventTitle'))
			->select($db->quoteName('e.startDateTime', 'startDateTime'))
			->select($db->quoteName('e.endDateTime', 'endDateTime'))
			->join(
				'LEFT',
				$db->quoteName('#__sdajem_events', 'e') . ' ON ' . $db->quoteName('e.id') . '=' . $db->quoteName('c.sdajem_event_id')
			);

		//Join over User as attendee
		$query->select($db->quoteName('at.username', 'userName'))
			->join(
				'LEFT',
				$db->quoteName('#__users', 'at') . ' ON ' . $db->quoteName('at.id') . ' = ' . $db->quoteName('c.users_user_id')
			);

		// Filter on user. Default Current User
		if ($this->getState('filter.users_user_id'))
		{
			if ($user = $this->getState('filter.users_user_id'))
			{
				$query->where($db->quoteName('c.users_user_id') . ' = ' . $db->quote($user));
			}
		} else {
			$query->where($db->quoteName('c.users_user_id') . ' = ' . $db->quote($currentUser->id));
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
				$query->where('c.id = ' . (int) substr($search, 3));
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
}