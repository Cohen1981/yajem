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

use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Sda\Component\Sdajem\Site\Enums\IntAttStatusEnum;

class AttendeestatuslistField extends ListField
{
	protected $type='Attendeestatuslist';

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
		$value= 'title';

		// Add header.
		if (!$this->element['header']) {
			$header_title = Text::_('SDAJEM_SELECT_ATTENDING_STATUS');
		} else {
			$header_title=$this->element['header'];
		}

		$options[] = HTMLHelper::_('select.option', '', $header_title);

		// Build the field options.
		foreach (IntAttStatusEnum::cases() as $status) {
			$options[] = HTMLHelper::_('select.option', $status->value, Text::_($status->getAttendingStatusLabel()));
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

}