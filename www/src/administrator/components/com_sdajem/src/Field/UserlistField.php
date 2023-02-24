<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Field
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 * @since       1.0.1
 */

namespace Sda\Component\Sdajem\Administrator\Field;

defined('_JEXEC') or die();

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Database\Exception\ExecutionFailureException;

class UserlistField extends ListField
{
	protected $type='Userlist';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.0.1
	 */
	protected function getOptions()
	{
		$options = [];

		$key = 'id';
		$value= 'username';

		// Get the database object.
		$db = $this->getDatabase();

		// Get the query object
		$query = $db->getQuery(true);

		$query->select(
			[
				$db->quoteName('u.id', 'id'),
				$db->quoteName('u.username', 'username')
			]
		);
		$query->from($db->quoteName('#__users', 'u'));

		// Join over the user_group_map.
		$query->select($db->quoteName('ugm.group_id', 'group_id'))
			->join(
				'INNER',
				$db->quoteName('#__user_usergroup_map', 'ugm') . ' ON ' . $db->quoteName('ugm.user_id') . ' = ' . $db->quoteName('u.id')
			);

		// Join over the user_group.
		$query->select($db->quoteName('ug.title', 'title'))
			->join(
				'INNER',
				$db->quoteName('#__usergroups', 'ug') . ' ON ' . $db->quoteName('ug.id') . ' = ' . $db->quoteName('ugm.group_id')
			);

		$params = ComponentHelper::getParams('com_sdajem');

		$group_name = '\'' . $params->get('sda_user_group_name') . '\'';

		$query->where($db->quoteName('ug.title') . ' = ' . $group_name);

		// Set the query and get the result list.
		$db->setQuery($query);

		try {
			$items = $db->loadObjectList();
		} catch (ExecutionFailureException $e) {
			Factory::getApplication()->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		}

		// Add header.
		if (!$this->element['header']) {
			$header_title = Text::_('SDAJEM_SELECT_USER');
		} else {
			$header_title=$this->element['header'];
		}

		$options[] = HTMLHelper::_('select.option', '', $header_title);

		// Build the field options.
		if (!empty($items)) {
			foreach ($items as $item) {
					$options[] = HTMLHelper::_('select.option', $item->$key, $item->$value);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

}