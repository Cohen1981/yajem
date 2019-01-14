<?php
/**
 * @package     Yajem.Administrator
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * @package     Yajem
 *
 * @since       version
 */
class JFormFieldLocations extends JFormFieldList
{
	/**
	 * @var string Name of the custom Field
	 * @since version
	 */
	protected $type = 'Locations';

	/**
	 * Get the List of locations for selection
	 * @return array    key->value
	 *
	 * @since version
	 * @throws Exception
	 */
	public function getOptions()
	{
		$app = JFactory::getApplication();
		$location = $app->input->get('location');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, title')->from('`#__yajem_locations`');

		if ($location)
		{
			$query->where('id = ' . $location->id);
		}

		$rows = $db->setQuery($query)->loadObjectlist();

		foreach ($rows as $row)
		{
			$locations[] = JHtml::_('select.option', $row->id, $row->title);
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $locations);

		return $options;
	}
}
