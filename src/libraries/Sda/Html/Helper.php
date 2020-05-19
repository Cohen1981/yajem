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
use Sda\Jem\Admin\Model\Event;

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
		return "<i class=\"fas fa-check\" aria-hidden=\"true\" title=\"" . Text::_('COM_SDAJEM_ICON_DATE') . "\"></i>";
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
		return "<i class=\"fas fa-times\" aria-hidden=\"true\" title=\"" . Text::_('COM_SDAJEM_ICON_TITLE') . "\"></i>";
	}

	/**
	 * Generates a link tag for edit or item view
	 *
	 * @param string $component the consuming component eg. com_sdajem
	 * @param string $view the consuming view
	 * @param int $itemId the itemId
	 * @param bool $edit set true if you want edit view; default = false
	 * @param string $name the Link text; default = ''
	 * @param bool $closedTag default = true closes the generate <a> tag with </a>
	 *
	 * @return string ready to render string
	 *
	 * @since 1.0.0
	 */
	public static function itemLink(string $component,string $view = '', int $itemId, bool $edit = false, string $name = '', bool $closedTag = true)
	{
		$url = "index.php?option=" . $component;
		if (!$view == '') {
			$url = $url . "&view=" . $view;
		}
		if ($edit) {
			$url = $url . "&task=edit";
		} else {
			$url = $url . "&task=read";
		}
		$url = $url . "&id=" . (int)$itemId;
		$linkText = "<a href=\"" . JRoute::_($url) . "\">" . $name;
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
	 * @return string
	 * @since 1.3.0
	 */
	public static function getEventCanceledByHostSymbol() {
		return "<div class='sda_event_cancelled_host'><span><b>Veranstaltung abgesagt !</b></span></div>";
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

	/**
	 * @return string
	 * @since 1.0.0
	 */
	public static function getEditSymbol()
	{
		return "<i class=\"fas fa-pen\" aria-hidden='true' title='" . Text::_('JACTION_EDIT') . "'></i> ";
	}

	/**
	 * @return string
	 * @since 1.0.0
	 */
	public static function getDeleteSymbol()
	{
		return "<i class=\"fas fa-trash\" aria-hidden='true' title='" . Text::_('JACTION_DELETE') . "'></i> ";
	}

	/**
	 * @param string $label
	 * @param array $options
	 *
	 * @return string
	 *
	 * @since 1.1.0
	 */
	public static function getFilter(string $label, array $options) : string {
		$html = "<label for=\"filter_type" . Text::_($label) . "\">" . Text::_($label) . "</label>";
		$html = $html . "<select id=\"filter_type" . Text::_($label) . "\" name=\"filter_type\" class=\"sda_filter\" onchange=\"multiFilter()\">";
		$html = $html . "<option value=\"\">" . Text::_('COM_SDA_FILTER_ALL') . "</option>";

		foreach ($options as $option) {
			$html = $html . "<option>" . str_replace(' ', '_', $option) . "</option>";
		}
		$html = $html . "</select>";

		return $html;
	}

	public static function getEventStatusChanger(Event $event) : string {
		$return = "";
		$checkedButton = "
			<button id=\"checkedButton\" 
					type=\"button\" 
					onclick=\"changeEventStatus(3," . $event->sdajem_event_id . ")\" 
					name=\"checkedButton\">"
			. self::getCheckedSymbol()
			. "</button>"
		;
		$confirmButton = "
			<button id=\"confirmButton\" 
					type=\"button\" 
					onclick=\"changeEventStatus(1," . $event->sdajem_event_id . ")\" 
					name=\"confirmButton\">"
			. self::getConfirmedSymbol()
			. "</button>"
		;
		$cancelButton = "
			<button id=\"cancelButton\" 
					type=\"button\" 
					onclick=\"changeEventStatus(2,$event->sdajem_event_id)\" 
					name=\"cancelButton\">"
			. self::getCanceledSymbol()
			. "</button>"
		;

		switch ($event->eventStatus)
		{
			case 0:
				$return = $return . $checkedButton;
				$return = $return . $confirmButton;
				$return = $return . $cancelButton;
				break;
			case 1:
				$return = $return . $cancelButton;
				break;
			case 2:
				$return = $return . $confirmButton;
				break;
			case 3:
				$return = $return . $confirmButton;
				$return = $return . $cancelButton;
				break;
		}

		return $return;
	}
}