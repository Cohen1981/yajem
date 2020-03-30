<?php

namespace Sda\Html;

/**
 * @package     Sda\Html
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use FOF30\Container\Container;
use Joomla\CMS\Language\Text;
use JRoute;

/**
 * Class for lazy people.
 *
 * @package     Sda\Html
 *
 * @since       1.0.0
 */
abstract class Helper
{
	/**
	 * Icon for a boolean state
	 *
	 * @param int $state the integer representation of a boleean state
	 * @return string ready to render icon for a boolean state
	 * @since 1.0.2
	 */
	public static function booleanIcon(int $state = 0)
	{
		return ($state == 0) ? Helper::falseIcon() : Helper::trueIcon();
	}

	/**
	 * Icon for true state.
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public static function trueIcon()
	{
		return "<i class=\"fas fa-calendar-alt\" aria-hidden=\"true\" title=\"" . Text::_('COM_SDAJEM_ICON_DATE') . "\"></i>";
	}

	/**
	 * Icon for false State
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public static function falseIcon()
	{
		return "<i class=\"fas fa-bookmark\" aria-hidden=\"true\" title=\"" . Text::_('COM_SDAJEM_ICON_TITLE') . "\">&nbsp;</i>";
	}

	/**
	 * Generates a link tag for edit or item view
	 *
	 * @param string $component the consuming component eg. com_sdajem
	 * @param int $itemId the itemId
	 * @param bool $edit set true if you want edit view; default = false
	 * @param string $name the Link text; default = ''
	 * @param bool $closedTag default = true closes the generate <a> tag with </a>
	 *
	 * @return string ready to render string
	 *
	 * @since 1.0.0
	 */
	public static function itemLink(string $component, int $itemId, bool $edit = false, string $name = '', bool $closedTag = true)
	{
		if ($edit) {
			$linkText = "<a href=\"" . JRoute::_('index.php?option=' . $component . ' &task=edit&id=' . (int)$itemId . '\'') . ">" . $name;
		} else {
			$linkText = "<a href=\"" . JRoute::_('index.php?option=' . $component . ' &task=read&id=' . (int)$itemId . '\'') . ">" . $name;
		}

		if ($closedTag) {
			$linkText = $linkText . "</a>";
		}

		return $linkText;
	}

	/**
	 * Returns a img tag
	 *
	 * @param string $imagePath path to the image
	 * @param string $class class for the tag; default = ''
	 *
	 * @return string ready to render img tag
	 *
	 * @since 1.0.0
	 */
	public static function imgTag(string $imagePath, string $class = '') {
		return "<img class=\"" . $class . "\" src=\"" . $imagePath . "\"/>";
	}

	/**
	 * Get the Symbol for an open event. (gather interest)
	 *
	 * @return string
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
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
	 * @since 1.0.0
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
	 * @since 1.0.0
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
	 * @since 1.0.0
	 */
	public static function getEventStatusSymbol(int $status)
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
	 * @since 1.0.0
	 */
	public static function getEvetStatusTextByStatus(int $status)
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
}