<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @package     Sda\Component\Sdajem\Site\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Helper;

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Sda\Component\Sdajem\Site\Model\EventAttendeeModel;
use Sda\Component\Sdajem\Site\Model\EventInterestModel;

abstract class EventHtmlHelper
{
	public static function renderAttendee(EventAttendeeModel $attendeeModel, string $fieldName = null) {
		echo '<div class="card" style="width: 120px;">';
		if (!is_null($fieldName))
		{
			echo $attendeeModel->userData[$fieldName]->value;
		}
        echo '<div class="card-body">';
		echo '<h5 class="card-title">' . $attendeeModel->user->username . '</h5>';
        echo '<p class="card-text">' . $attendeeModel->status->getAttendingStatusBadge() . '</p>';
        echo '</div></div>';
	}

	public static function renderInterest(EventInterestModel $interestModel, string $fieldName = null) {
		echo '<div class="card" style="width: 120px;">';
		if (!is_null($fieldName))
		{
			echo $interestModel->userData[$fieldName]->value;
		}
		echo '<div class="card-body">';
		echo '<h5 class="card-title">' . $interestModel->user->username . '</h5>';
		echo '<p class="card-text">' . $interestModel->status->getInterestStatusBadge() . '</p>';
		echo '</div></div>';
	}

	public static function renderFitting($fitting) {
		echo '<div class="card" style="width: 120px;">';
		echo HTMLHelper::image($fitting->image,'');
		echo '<div class="card-body">';
		echo '<h5 class="card-title">' . $fitting->title . '</h5>';
		echo '<p class="card-text">' . $fitting->description . '</p>';
		echo '</div></div>';
	}
}