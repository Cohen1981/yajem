<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Model
 * @subpackage  com_sdajem
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

/**
 * @package     Sda\Component\Sdajem\Administrator\Model
 *
 * @since       __BUMP_VERSION__
 */
class EventsModel extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     \JControllerLegacy
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function __construct($config = [])
	{
		parent::__construct($config);
	}
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  \JDatabaseQuery
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
			$db->quoteName(['a.id', 'a.title', 'a.alias', 'a.access', 'a.catid', 'a.published', 'a.publish_up', 'a.publish_down'])
		);
		$query->from($db->quoteName('#__sda_events', 'a'));

		// Join over the categories.
		$query->select($db->quoteName('c.title', 'category_title'))
			->join(
				'LEFT',
				$db->quoteName('#__categories', 'c') . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid')
			);

		// Join over the asset groups.
		$query->select($db->quoteName('ag.title', 'access_level'))
			->join(
				'LEFT',
				$db->quoteName('#__viewlevels', 'ag') . ' ON ' . $db->quoteName('ag.id') . ' = ' . $db->quoteName('a.access')
			);

		return $query;
	}
}