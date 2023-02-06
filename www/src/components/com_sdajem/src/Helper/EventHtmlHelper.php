<?php
/**
 * @package     Sda\Component\Sdajem\Site\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Helper;

use Joomla\CMS\Language\Text;
use Sda\Component\Sdajem\Site\Model\AttendeeModel;

abstract class EventHtmlHelper
{
	public static function renderAttendee(AttendeeModel $attendeeModel, string $fieldName = null) {
		echo '<div class="sda_attendee">';

		if (!is_null($fieldName)) {
			echo $attendeeModel->userData[$fieldName]->value;
		}

		echo '<div>' . $attendeeModel->user->name . '</div>';
		echo '<div>' . Text::_($attendeeModel->status->getStatusLabel()) . '</div>';
		echo '</div>';
	}
}