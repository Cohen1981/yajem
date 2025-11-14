<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

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

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Database\Exception\ExecutionFailureException;

class EventlistField extends ListField
{
	protected $type='Eventlist';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @throws \Exception
	 * @since   1.0.1
	 */
	protected function getOptions()
	{
		$options = [];

		$key = 'id';

		// Get the database object.
		$db = $this->getDatabase();

		// Get the query object
		$query = $db->getQuery(true);

		$query->select(
			[
				$db->quoteName('e.id', 'id'),
				$db->quoteName('e.title', 'title'),
				$db->quoteName('e.startDateTime', 'startDate'),
				$db->quoteName('e.endDateTime', 'endDate')
			]
		);
		$query->from($db->quoteName('#__sdajem_events', 'e'));

		// Set the query and get the result list.
		$db->setQuery($query);

		try {
			$items = $db->loadObjectList();
		} catch (ExecutionFailureException $e) {
			Factory::getApplication()->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		}

		// Add header.
		if (!$this->element['header']) {
			$header_title = Text::_('SDAJEM_SELECT_EVENT');
		} else {
			$header_title=$this->element['header'];
		}

		$options[] = HTMLHelper::_('select.option', '', $header_title);

		// Build the field options.
		if (!empty($items)) {
			foreach ($items as $item) {
					$startDate = Factory::getDate($item->startDate,'UTC');
					$endDate = Factory::getDate($item->endDate,'UTC');
					$options[] = HTMLHelper::_('select.option', $item->$key, $item->title . ' ' . $startDate->format('d.m.Y') . ' - ' .$endDate->format('d.m.Y'));
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

}