<?php

/**
 * @copyright (c) 2025 Alexander Bahlo <abahlo@hotmail.de>
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sda\Component\Sdajem\Administrator\Model;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\QueryInterface;
use Sda\Component\Sdajem\Administrator\Library\Collection\EventsCollection;
use Sda\Component\Sdajem\Administrator\Library\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Administrator\Library\Enums\IntAttStatusEnum;

use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @package     Sda\Component\Sdajem\Administrator\Model
 * @since       1.0.1
 */
class EventsModel extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @since 1.0.1
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @throws \Exception
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id',
				'a.id',
				'title',
				'a.title',
				'published',
				'a.published',
				'access',
				'a.access',
				'access_level',
				'ordering',
				'a.ordering',
				'publish_up',
				'a.publish_up',
				'publish_down',
				'a.publish_down',
				'startDateTime',
				'a.startDateTime',
				'endDateTime',
				'a.endDateTime',
				'eventStatus',
				'a.eventStatus',
				'attendeeCount'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @since   1.0.0
	 * @return  QueryInterface
	 * @throws \Exception
	 */
	protected function getListQuery(): QueryInterface
	{
		$params = ComponentHelper::getParams('com_sdajem');

		// Create a new query object.
		$db    = $this->getDatabase();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				[
					$db->quoteName('a.id'),
					$db->quoteName('a.access'),
					$db->quoteName('a.alias'),
					$db->quoteName('a.created'),
					$db->quoteName('a.created_by'),
					$db->quoteName('a.published'),
					$db->quoteName('a.publish_up'),
					$db->quoteName('a.publish_down'),
					$db->quoteName('a.state'),
					$db->quoteName('a.ordering'),
					$db->quoteName('a.title'),
					$db->quoteName('a.description'),
					$db->quoteName('a.url'),
					$db->quoteName('a.startDateTime'),
					$db->quoteName('a.endDateTime'),
					$db->quoteName('a.allDayEvent'),
					$db->quoteName('a.sdajem_location_id'),
					$db->quoteName('a.image'),
					$db->quoteName('a.eventStatus'),
					$db->quoteName('a.organizerId'),
					$db->quoteName('a.registerUntil'),
					$db->quoteName('a.hostId'),
					$db->quoteName('a.eventCancelled'),
					$db->quoteName('a.params'),
					$db->quoteName('a.svg'),
				]
			)
		);
		$query->from($db->quoteName('#__sdajem_events', 'a'));

		// Join over locations
		$query->select(
			$this->getState(
				'list.select',
				[
					$db->quoteName('loc.title', 'locationName'),
					$db->quoteName('loc.postalCode', 'postalCode')
				]
			)
		)
			->join(
				'LEFT',
				$db->quoteName('#__sdajem_locations', 'loc') . ' ON ' . $db->quoteName(
					'loc.id'
				) . ' = ' . $db->quoteName('a.sdajem_location_id')
			);

		// Join over User as organizer
		$query->select($db->quoteName('org.username', 'organizerName'))
			->join(
				'LEFT',
				$db->quoteName('#__users', 'org') . ' ON ' . $db->quoteName('org.id') . ' = ' . $db->quoteName(
					'a.organizerId'
				)
			);

		// Get the Attendee count
		$attendees = $db->getQuery(true)
			->select('COUNT(' . $db->quoteName('att.id') . ')')
			->from($db->quoteName('#__sdajem_attendings', 'att'))
			->where(
				[
					$db->quoteName('att.event_id') . ' = ' . $db->quoteName('a.id'),
					$db->quoteName('att.status') . ' = ' . IntAttStatusEnum::POSITIVE->value,
					$db->quoteName('att.event_status') . ' = ' . EventStatusEnum::OPEN->value
				]
			);
		$query->select('(' . $attendees . ') AS ' . $db->quoteName('attendeeCount'));

		// Get the guest count
		$guests = $db->getQuery(true)
			->select('COUNT(' . $db->quoteName('g.id') . ')')
			->from($db->quoteName('#__sdajem_attendings', 'g'))
			->where(
				[
					$db->quoteName('g.event_id') . ' = ' . $db->quoteName('a.id'),
					$db->quoteName('g.status') . ' = ' . IntAttStatusEnum::GUEST->value,
					$db->quoteName('g.event_status') . ' = ' . EventStatusEnum::OPEN->value
				]
			);
		$query->select('(' . $guests . ') AS ' . $db->quoteName('guestCount'));

		// Get the Attendee feedback count
		$attendeesf = $db->getQuery(true)
			->select('COUNT(' . $db->quoteName('atte.id') . ')')
			->from($db->quoteName('#__sdajem_attendings', 'atte'))
			->where(
				[
					$db->quoteName('atte.event_id') . ' = ' . $db->quoteName('a.id'),
					$db->quoteName(
						'atte.status'
					) . ' IN( ' . IntAttStatusEnum::POSITIVE->value . ',
							 ' . IntAttStatusEnum::NEGATIVE->value . ',
							  ' . IntAttStatusEnum::GUEST->value . ')',
					$db->quoteName('atte.event_status') . ' = ' . EventStatusEnum::OPEN->value
				]
			);
		$query->select('(' . $attendeesf . ') AS ' . $db->quoteName('attendeeFeedbackCount'));

		$interestCount = $db->getQuery(true)
			->select('COUNT(' . $db->quoteName('int.id') . ')')
			->from($db->quoteName('#__sdajem_attendings', 'int'))
			->where(
				[
					$db->quoteName('int.event_id') . ' = ' . $db->quoteName('a.id'),
					$db->quoteName('int.status') . ' = ' . IntAttStatusEnum::POSITIVE->value,
					$db->quoteName('int.event_status') . ' = ' . EventStatusEnum::PLANING->value
				]
			);
		$query->select('(' . $interestCount . ') AS ' . $db->quoteName('interestCount'));

		$feedback = $db->getQuery(true)
			->select('COUNT(' . $db->quoteName('i.id') . ')')
			->from($db->quoteName('#__sdajem_attendings', 'i'))
			->where(
				[
					$db->quoteName('i.event_id') . ' = ' . $db->quoteName('a.id'),
					$db->quoteName('i.event_status') . ' = ' . EventStatusEnum::PLANING->value
				]
			);
		$query->select('(' . $feedback . ') AS ' . $db->quoteName('feedbackCount'));

		// Join over the asset groups.
		$query->select($db->quoteName('ag.title', 'accessLevel'))
			->join(
				'LEFT',
				$db->quoteName('#__viewlevels', 'ag') . ' ON ' . $db->quoteName(
					'ag.id'
				) . ' = ' . $db->quoteName('a.access')
			);

		// Filter by access level.
		if ($this->getState('filter.access', true))
		{
			$groups = $this->getState(
				'filter.viewlevels',
				Factory::getApplication()->getIdentity()->getAuthorisedViewLevels()
			);
			$query->whereIn($db->quoteName('a.access'), $groups);
		}

		// Filter by published state
		$published = (string) $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where($db->quoteName('a.published') . ' = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(' . $db->quoteName('a.published') . ' = 0 OR ' . $db->quoteName('a.published') . ' = 1)');
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
					'(' . $db->quoteName('a.title') . ' LIKE ' . $search . ')'
				);
			}
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'a.startDateTime');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'a.ordering' || $orderCol == 'category_title')
		{
			$orderCol = $db->quoteName('c.title') . ' ' . $orderDirn . ', ' . $db->quoteName('a.ordering');
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Retrieves a collection of items.
	 *
	 * @since 1.0.1
	 * @return EventsCollection the collection of items.
	 * A collection of items on success, or false on failure.
	 */
	public function getItems(): EventsCollection
	{
		return new EventsCollection(parent::getItems());
	}
}
