<?php
/**
 * @package     Sda\Jem\Admin\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Admin\Helper;


use FOF30\Container\Container;
use Joomla\CMS\Language\Text;
use mysql_xdevapi\BaseResult;
use Sda\Jem\Admin\Model\Attendee;

/**
 * Helper Class for event status. Getting the right symbol and text.
 *
 * @package     Sda\Jem\Admin\Helper
 *
 * @since       0.1.5
 */
abstract class EventStatusHelper
{
	/**
	 * Get the Symbol for an open event. (gather interest)
	 *
	 * @return string
	 *
	 * @since 0.1.5
	 */
	public static function getOpenSymbol()
	{
		return "<i class=\"fas fa-question-circle\" aria-hidden='true' title='" . Text::_('COM_SDAJEM_ICON_STATUS_OPEN') . "'></i> ";
	}

	/**
	 * Get the symbol for a registration request has been sended to the event hoster
	 *
	 * @return string
	 *
	 * @since 0.1.5
	 */
	public static function getCheckedSymbol()
	{
		return "<i class=\"fas fa-exclamation-circle\" aria-hidden='true' title='" . Text::_('COM_SDAJEM_ICON_STATUS_CHECKED') . "'></i> ";
	}

	/**
	 * Get the symbol for event is confirmed
	 *
	 * @return string
	 *
	 * @since 0.1.5
	 */
	public static function getConfirmedSymbol()
	{
		return "<i class=\"fas fa-check-circle\" aria-hidden='true' title='" . Text::_('COM_SDAJEM_ICON_STATUS_CONFIRMED') . "'></i> ";
	}

	/**
	 * Get the symbol for event is canceled
	 *
	 * @return string
	 *
	 * @since 0.1.5
	 */
	public static function getCanceledSymbol()
	{
		return "<i class=\"fas fa-times-circle\" aria-hidden='true' title='" . Text::_('COM_SDAJEM_ICON_STATUS_CANCELED') . "'></i> ";
	}

	/**
	 * Get the status symbol by event->status
	 *
	 * @param   int $status The event status
	 *
	 * @return string Status symbol
	 *
	 * @since 0.1.5
	 */
	public static function getSymbolByStatus(int $status)
	{
		$symbol = '';

		switch ($status)
		{
			case 0:
				$symbol = self::getOpenSymbol();
				break;
			case 1:
				$symbol = self::getConfirmedSymbol();
				break;
			case 2:
				$symbol = self::getCanceledSymbol();
				break;
			case 3:
				$symbol = self::getCheckedSymbol();
				break;
		}

		return $symbol;
	}

	/**
	 * Get the status text for an event->status
	 *
	 * @param   int $status The event status
	 *
	 * @return string Status text
	 *
	 * @since 0.1.5
	 */
	public static function getStatusTextByStatus(int $status)
	{
		$text = '';

		switch ($status)
		{
			case 0:
				$text = Text::_('SDAJEM_EVENT_STATUS_OPEN');
				break;
			case 1:
				$text = Text::_('SDAJEM_EVENT_STATUS_CONFIRMED');
				break;
			case 2:
				$text = Text::_('SDAJEM_EVENT_STATUS_CANCELED');
				break;
			case 3:
				$text = Text::_('COM_SDAJEM_EVENT_STATUS_CHECKED');
				break;
		}

		return $text;
	}

	/**
	 * @param int $userId
	 * @param int $eventId
	 * @return string
	 * @since 0.7.0
	 */
	public static function getStatusLabel(int $userId, int $eventId) : string
	{
		/** @var Attendee $attendeeModel */
		$attendeeModel = Container::getInstance('com_sdajem')->factory->model('Attendee');
		$attendeeModel->getAttendeeForEventAndUser($userId, $eventId);
		$statusLabel="";
		switch ($attendeeModel->status)
		{
			case 0:
				$statusLabel = "<span class=\"sdajem_status_label sdajem_grey\">" . Text::_('COM_SDAJEM_UNDECIDED') . "</span>";
				break;
			case 1:
				$statusLabel = "<span class=\"sdajem_status_label sdajem_green\">" . Text::_('COM_SDAJEM_ATTENDING') . "</span>";
				break;
			case 2:
				$statusLabel = "<span class=\"sdajem_status_label sdajem_red\">" . Text::_('COM_SDAJEM_NATTENDING') . "</span>";
				break;
		}

		return $statusLabel;
	}
}