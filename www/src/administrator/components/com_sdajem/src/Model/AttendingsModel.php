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
use Joomla\CMS\Language\Associations;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\QueryInterface;
use Joomla\Utilities\ArrayHelper;

class AttendingsModel extends ListModel
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'event_id', 'a.event_id', 'e.title', 'eventTitle',
				'users_user_is', 'a.users_user_id', 'at.username', 'attendeeName',
				'Status', 'a.Status'
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

		return $query;
	}
}