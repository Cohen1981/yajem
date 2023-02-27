<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Model;

use Joomla\Database\QueryInterface;

class FittingtypesModel extends \Joomla\CMS\MVC\Model\ListModel
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
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
					$db->quoteName('a.access'),
					$db->quoteName('a.alias'),
					$db->quoteName('a.created_by'),
					$db->quoteName('a.created_by_alias'),
					$db->quoteName('a.published'),
					$db->quoteName('a.publish_up'),
					$db->quoteName('a.publish_down'),
					$db->quoteName('a.state'),
					$db->quoteName('a.ordering'),
					$db->quoteName('a.title'),
					$db->quoteName('a.description'),
					$db->quoteName('a.needSpace'),
					$db->quoteName('a.image')
				]
			)
		);
		$query->from($db->quoteName('#__sdajem_fittingtypes', 'a'));

		// Join over the asset groups.
		$query->select($db->quoteName('ag.title', 'access_level'))
			->join(
				'LEFT',
				$db->quoteName('#__viewlevels', 'ag') . ' ON ' . $db->quoteName('ag.id') . ' = ' . $db->quoteName('a.access')
			);

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
					'(' . $db->quoteName('a.title') . ' LIKE ' . $search . ')'
				);
			}
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.title');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}