<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Field
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Field;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Database\Exception\ExecutionFailureException;

class FittingtypelistField extends \Joomla\CMS\Form\Field\ListField
{
	protected $type='Fittingtypelist';

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
		$value = 'title';

		// Get the database object.
		$db = $this->getDatabase();

		// Get the query object
		$query = $db->getQuery(true);

		$query->select(
			[
				$db->quoteName('ft.id', 'id'),
				$db->quoteName('ft.title', 'title'),
			]
		);
		$query->from($db->quoteName('#__sdajem_fittingtypes', 'ft'));

		// Set the query and get the result list.
		$db->setQuery($query);

		try {
			$items = $db->loadObjectList();
		} catch (ExecutionFailureException $e) {
			Factory::getApplication()->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		}

		// Add header.
		if (!$this->element['header']) {
			$header_title = Text::_('SDAJEM_SELECT_FITTING_TYPE');
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