<?php
/**
 * @package     Sda\Jem\Admin\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Admin\Helper;


use Joomla\CMS\Language\Text;

/**
 * @package     Sda\Jem\Admin\Helper
 *
 * @since       0.1.5
 */
abstract class EventStatusHelper
{
	/**
	 *
	 * @return string
	 *
	 * @since 0.1.5
	 */
	public static function getOpenSymbol()
	{
		return "<i class=\"far fa-circle\" aria-hidden='true' title='" . Text::_('COM_SDAJEM_ICON_STATUS_OPEN') . "'></i> ";
	}

	/**
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
	 * @param   int $status The Status
	 *
	 * @return string
	 *
	 * @since 0.1.5
	 */
	public static function getSymbolByStatus(int $status)
	{
		switch ($status)
		{
			case 0:
				return self::getOpenSymbol();
				break;
			case 1:
				return self::getConfirmedSymbol();
				break;
			case 2:
				return self::getCanceledSymbol();
				break;
			case 3:
				return self::getCheckedSymbol();
				break;
		}
	}

	/**
	 * @param   int $status The Status
	 *
	 * @return string
	 *
	 * @since 0.1.5
	 */
	public static function getStatusTextByStatus(int $status)
	{
		switch ($status)
		{
			case 0:
				return Text::_('SDAJEM_EVENT_STATUS_OPEN');
				break;
			case 1:
				return Text::_('SDAJEM_EVENT_STATUS_CONFIRMED');
				break;
			case 2:
				return Text::_('SDAJEM_EVENT_STATUS_CANCELED');
				break;
			case 3:
				return Text::_('COM_SDAJEM_EVENT_STATUS_CHECKED');
				break;
		}
	}
}