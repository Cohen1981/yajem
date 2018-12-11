<?php
/**
 * @package     Yajem.Site
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemModelLocations extends ListModel
{
	/**
	 * Get the location for a given location Id
	 * @param   int $id The location Id
	 *
	 * @return mixed the location Object
	 *
	 * @since version
	 */
	public function getLocation($id)
	{
		$db = JFactory::getDbo();

		$locQuery = $db->getQuery(true);

		$locQuery->select('l.title, l.description, l.url,
			l.street, l.postalCode, l.city, l.image, l.latlng'
		);
		$locQuery->from('#__yajem_locations AS l');
		$locQuery->where('l.id = ' . $id);
		$locQuery->select('cd.id as contactId, cd.name AS contact');
		$locQuery->join('LEFT', '#__contact_details AS cd ON cd.id = contactId');
		$db->setQuery($locQuery);

		return $db->loadObject();
	}
}