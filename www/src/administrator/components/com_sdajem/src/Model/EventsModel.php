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
			);
			$assoc = Associations::isEnabled();
			if ($assoc)
			{
				$config['filter_fields'][] = 'association';
			}
		}

		parent::__construct($config);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  QueryInterface
	 *
	 * @since   __BUMP_VERSION__
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
					$db->quoteName('a.title'),
					$db->quoteName('a.alias'),
					$db->quoteName('a.description'),
					$db->quoteName('a.url'),
					$db->quoteName('a.access'),
					$db->quoteName('a.catid'),
					$db->quoteName('a.published'),
					$db->quoteName('a.publish_up'),
					$db->quoteName('a.publish_down'),
					$db->quoteName('a.ordering'),
					$db->quoteName('a.language'),
					$db->quoteName('a.startDateTime'),
					$db->quoteName('a.endDateTime'),
					$db->quoteName('a.allDayEvent'),
					$db->quoteName('a.sdajem_location_id')
				]
			)
		);
		$query->from($db->quoteName('#__sdajem_events', 'a'));

		$query->select($db->quoteName('loc.title', 'location_name'))
			->join(
				'LEFT',
				$db->quoteName('#__sdajem_locations', 'loc') . ' ON ' . $db->quoteName('loc.id') . ' = ' . $db->quoteName('a.sdajem_location_id')
			);
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

		// Join over the associations.
		if (Associations::isEnabled())
		{
			$subQuery = $db->getQuery(true)
				->select('COUNT(' . $db->quoteName('asso1.id') . ') > 1')
				->from($db->quoteName('#__associations', 'asso1'))
				->join('INNER', $db->quoteName('#__associations', 'asso2'), $db->quoteName('asso1.key') . ' = ' . $db->quoteName('asso2.key'))
				->where(
					[
						$db->quoteName('asso1.id') . ' = ' . $db->quoteName('a.id'),
						$db->quoteName('asso1.context') . ' = ' . $db->quote('com_sdajem.item'),
					]
				);
			$query->select('(' . $subQuery . ') AS ' . $db->quoteName('association'));
		}
		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where($db->quoteName('a.language') . ' = ' . $db->quote($language));
		}
		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where($db->quoteName('a.access') . ' = ' . (int) $access);
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
		$orderCol = $this->state->get('list.ordering', 'a.title');
		$orderDirn = $this->state->get('list.direction', 'asc');
		if ($orderCol == 'a.ordering' || $orderCol == 'category_title')
		{
			$orderCol = $db->quoteName('c.title') . ' ' . $orderDirn . ', ' . $db->quoteName('a.ordering');
		}
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	protected function populateState($ordering = 'a.title', $direction = 'asc')
	{
		$app = Factory::getApplication();
		$forcedLanguage = $app->input->get('forcedLanguage', '', 'cmd');
		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}
		// Adjust the context to support forced languages.
		if ($forcedLanguage)
		{
			$this->context .= '.' . $forcedLanguage;
		}
		// List state information.
		parent::populateState($ordering, $direction);
		// Force a language.
		if (!empty($forcedLanguage))
		{
			$this->setState('filter.language', $forcedLanguage);
		}
	}
}