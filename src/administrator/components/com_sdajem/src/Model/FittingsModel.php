<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\QueryInterface;
use Joomla\Utilities\ArrayHelper;
use Sda\Component\Sdajem\Site\Enums\IntAttStatusEnum;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

class FittingsModel extends ListModel
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
					$db->quoteName('a.length'),
					$db->quoteName('a.width'),
					$db->quoteName('a.standard'),
					$db->quoteName('a.fittingType'),
					$db->quoteName('a.user_id'),
					$db->quoteName('a.image'),
					$db->quoteName('a.needSpace')
				]
			)
		);
		$query->from($db->quoteName('#__sdajem_fittings', 'a'));

		//Join over User
		$query->select($db->quoteName('u.username', 'userName'))
			->join(
				'LEFT',
				$db->quoteName('#__users', 'u') . ' ON ' . $db->quoteName('u.id') . ' = ' . $db->quoteName('a.user_id')
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

	public function getFittingsForUser($userId = null) {
		$userId = ($userId) ? $userId : Factory::getApplication()->getIdentity()->id;

		$db = $this->getDatabase();
		$query = $db->getQuery(true);

		$query->select(
			$this->getState(
				'list.select',
				[
					$db->quoteName('a.id'),
					$db->quoteName('a.title'),
					$db->quoteName('a.description'),
					$db->quoteName('a.length'),
					$db->quoteName('a.width'),
					$db->quoteName('a.standard'),
					$db->quoteName('a.fittingType'),
					$db->quoteName('a.user_id'),
					$db->quoteName('a.image'),
					$db->quoteName('a.needSpace'),
				]
			)
		);
		$query->from($db->quoteName('#__sdajem_fittings', 'a'));

		$query->where($db->quoteName('a.user_id') . '= :userId');
		$query->bind(':userId', $userId);

		$db->setQuery($query);
		$data = $db->loadObjectList();

		return $data;
	}

	/**
	 * Get all fittings for given event
	 *
	 * @param   int  $eventId
	 * @since   1.1.4
	 */
	public function getFittingsForEvent(int $eventId) {
		$db = $this->getDatabase();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('att.fittings', 'fittings'));
		$query->from($db->quoteName('#__sdajem_attendings', 'att'));
		$query->where($db->quoteName('att.event_id') . '= :eventId');
		$query->extendWhere('AND', '(' . $db->quoteName('att.status') . ' = ' . IntAttStatusEnum::POSITIVE->value . ')');
		$query->bind(':eventId', $eventId);

		$db->setQuery($query);
		$fittings = $db->loadColumn();

		$pks = array();
		foreach ($fittings as $i => $value) {
			$ids = json_decode($value, true);
			if (is_array($ids))
			{
				$pks = array_merge($pks, $ids);
			}
		}

		if (count($pks) > 0)
		{
			$query = $db->getQuery(true);

			$query->select(
				$this->getState(
					'list.select',
					[
						$db->quoteName('a.id'),
						$db->quoteName('a.title'),
						$db->quoteName('a.description'),
						$db->quoteName('a.length'),
						$db->quoteName('a.width'),
						$db->quoteName('a.standard'),
						$db->quoteName('a.fittingType'),
						$db->quoteName('a.user_id'),
						$db->quoteName('a.image'),
						$db->quoteName('a.needSpace'),
					]
				)
			);
			$query->from($db->quoteName('#__sdajem_fittings', 'a'));
			$query->select($db->quoteName('u.username', 'userName'))
				->join(
					'LEFT',
					$db->quoteName('#__users',
						'u') . ' ON ' . $db->quoteName('u.id') . ' = ' . $db->quoteName('a.user_id')
				);
			$query->where($db->quoteName('a.id') . ' IN (' . implode(',', ArrayHelper::toInteger($pks)) . ')');
			$db->setQuery($query);
			$data = $db->loadObjectList();
		} else {
			$data = array();
		}
		return $data;
	}
}