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
use Joomla\Utilities\ArrayHelper;
use Sda\Component\Sdajem\Site\Enums\IntAttStatusEnum;

/**
 * @since       1.0.1
 * @package     Sda\Component\Sdajem\Administrator\Model
 *
 */
class EventsModel extends ListModel
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'catid', 'a.catid', 'category_id', 'category_title',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'ordering', 'a.ordering',
				'language', 'a.language', 'language_title',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
				'startDateTime', 'a.startDateTime',
				'endDateTime', 'a.endDateTime',
				'eventStatus', 'a.eventStatus',
				'attendeeCount'
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
					$db->quoteName('a.alias'),
					$db->quoteName('a.id'),
					$db->quoteName('a.access'),
					$db->quoteName('a.alias'),
					$db->quoteName('a.created'),
					$db->quoteName('a.created_by'),
					$db->quoteName('a.created_by_alias'),
					$db->quoteName('a.checked_out'),
					$db->quoteName('a.checked_out_time'),
					$db->quoteName('a.published'),
					$db->quoteName('a.publish_up'),
					$db->quoteName('a.publish_down'),
					$db->quoteName('a.state'),
					$db->quoteName('a.ordering'),
					$db->quoteName('a.language'),
					$db->quoteName('a.title'),
					$db->quoteName('a.description'),
					$db->quoteName('a.url'),
					$db->quoteName('a.catid'),
					$db->quoteName('a.startDateTime'),
					$db->quoteName('a.endDateTime'),
					$db->quoteName('a.allDayEvent'),
					$db->quoteName('a.sdajem_location_id'),
					$db->quoteName('a.image'),
					$db->quoteName('a.eventStatus'),
					$db->quoteName('a.organizerId')
				]
			)
		);
		$query->from($db->quoteName('#__sdajem_events', 'a'));

		// Join over locations
		$query->select($db->quoteName('loc.title', 'location_name'))
			->join(
				'LEFT',
				$db->quoteName('#__sdajem_locations', 'loc') . ' ON ' . $db->quoteName('loc.id') . ' = ' . $db->quoteName('a.sdajem_location_id')
			);
		//Join over User as organizer
		$query->select($db->quoteName('org.username', 'organizerName'))
			->join(
				'LEFT',
				$db->quoteName('#__users', 'org') . ' ON ' . $db->quoteName('org.id') . ' = ' . $db->quoteName('a.organizerId')
			);

		//Get the Attendee count
		$attendees = $db->getQuery(true)
			->select('COUNT(' . $db->quoteName('att.id') . ')')
			->from($db->quoteName('#__sdajem_attendings', 'att'))
			->where(
				[
					$db->quoteName('att.event_id') . ' = ' . $db->quoteName('a.id'),
					$db->quoteName('att.status') . ' = ' . IntAttStatusEnum::POSITIVE->value,
				]
			);
		$query->select('(' . $attendees . ') AS ' . $db->quoteName('attendeeCount'));

		$interestCount = $db->getQuery(true)
			->select('COUNT(' . $db->quoteName('int.id') . ')')
			->from($db->quoteName('#__sdajem_interest', 'int'))
			->where(
				[
					$db->quoteName('int.event_id') . ' = ' . $db->quoteName('a.id'),
					$db->quoteName('int.status') . ' = ' . IntAttStatusEnum::POSITIVE->value,
				]
			);
		$query->select('(' . $interestCount . ') AS ' . $db->quoteName('interestCount'));

		$feedback = $db->getQuery(true)
			->select('COUNT(' . $db->quoteName('i.id') . ')')
			->from($db->quoteName('#__sdajem_interest', 'i'))
			->where(
				[
					$db->quoteName('i.event_id') . ' = ' . $db->quoteName('a.id'),
				]
			);
		$query->select('(' . $feedback . ') AS ' . $db->quoteName('feedbackCount'));
		// Join over the asset groups.
		$query->select($db->quoteName('ag.title', 'access_level'))
			->join(
				'LEFT',
				$db->quoteName('#__viewlevels', 'ag') . ' ON ' . $db->quoteName('ag.id') . ' = ' . $db->quoteName('a.access')
			);

		// Join over the categories.
		$query->select($db->quoteName('c.title', 'category_title'))
			->join(
				'LEFT',
				$db->quoteName('#__categories', 'c') . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid')
			);

		// Join over the language
		$query->select($db->quoteName('l.title', 'language_title'))
			->select($db->quoteName('l.image', 'language_image'))
			->join(
				'LEFT',
				$db->quoteName('#__languages', 'l') . ' ON ' . $db->quoteName('l.lang_code') . ' = ' . $db->quoteName('a.language')
			);

		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where($db->quoteName('a.language') . ' = ' . $db->quote($language));
		}
		// Filter by access level.
		if ($this->getState('filter.access', true))
		{
			$groups = $this->getState('filter.viewlevels', Factory::getApplication()->getIdentity()->getAuthorisedViewLevels());
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
		// Filter by a single or group of categories.
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId))
		{
			$query->where($db->quoteName('a.catid') . ' = ' . (int) $categoryId);
		}
		elseif (is_array($categoryId))
		{
			$query->where($db->quoteName('a.catid') . ' IN (' . implode(',', ArrayHelper::toInteger($categoryId)) . ')');
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
		$orderCol = $this->state->get('list.ordering', 'a.startDateTime');
		$orderDirn = $this->state->get('list.direction', 'asc');
		if ($orderCol == 'a.ordering' || $orderCol == 'category_title')
		{
			$orderCol = $db->quoteName('c.title') . ' ' . $orderDirn . ', ' . $db->quoteName('a.ordering');
		}
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}