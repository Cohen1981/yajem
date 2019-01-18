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
 * @package     COM_YAJEM
 *
 * @since       version
 */
class JFormFieldEvents extends JFormFieldList
{
	/**
	 * @var string Name of the custom Field
	 * @since 1.0
	 */
	protected $type = 'Events';

	/**
	 * Get the List of events for selection
	 * @return array    key->value
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function getOptions()
	{
		$app = JFactory::getApplication();
		$event = $app->input->get('event');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('title')->from('`#__yajem_events`');

		if ($event)
		{
			$query->where('id = ' . $event->id);
		}

		$rows = $db->setQuery($query)->loadObjectlist();

		foreach ($rows as $row)
		{
			$events[] = $row->title;
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $events);

		return $options;
	}
}