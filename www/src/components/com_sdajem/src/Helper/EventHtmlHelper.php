<?php
/**
 * @package     Sda\Component\Sdajem\Site\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Helper;

defined('_JEXEC') or die();

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Database\Exception\ExecutionFailureException;
use Sda\Component\Sdajem\Site\Model\EventAttendeeModel;

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
        echo '<p class="card-text">' . Text::_($attendeeModel->status->getStatusLabel()) . '</p>';
        echo '</div></div>';
	}

	public static function getHostCategory() {
		$db = Factory::getDbo();
		$params = ComponentHelper::getParams('com_sdajem');

		$query = $db->getQuery(true);

		$query->select($db->quoteName('id'));
		$query->from($db->quoteName('#__categories'));
		$query->where($db->quoteName('title') . '=\'' . $params->get('sda_host_category_name') . '\'');

		$db->setQuery($query);

		try {
			$item = $db->loadResult();
		} catch (ExecutionFailureException $e) {
			Factory::getApplication()->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		}

		echo $item;
	}
}