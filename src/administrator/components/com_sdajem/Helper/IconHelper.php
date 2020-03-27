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
 * Class for lazy people. Collection of reused Icons
 *
 * @package     Sda\Jem\Admin\Helper
 *
 * @since       0.5.2
 */
abstract class IconHelper
{
	/**
	 * Calender/ Date icon.
	 *
	 * @return string
	 *
	 * @since 0.5.2
	 */
	public static function dateIcon()
	{
		return "<i class=\"fas fa-calendar-alt\" aria-hidden=\"true\" title=\"" . Text::_('COM_SDAJEM_ICON_DATE') . "\"></i>";
	}

	/**
	 * Bookmark Icon
	 *
	 * @return string
	 *
	 * @since 0.5.2
	 */
	public static function titleIcon()
	{
		return "<i class=\"fas fa-bookmark\" aria-hidden=\"true\" title=\"" . Text::_('COM_SDAJEM_ICON_TITLE') . "\">&nbsp;</i>";
	}

	/**
	 * A Map marker icon
	 *
	 * @return string
	 *
	 * @since 0.5.2
	 */
	public static function locationIcon()
	{
		return "<i class=\"fas fa-map-marker-alt\" aria-hidden=\"true\" title=\"" . Text::_('COM_SDAJEM_ICON_MAP') . "\">&nbsp;</i>";
	}

	/**
	 * flag Icon
	 *
	 * @return string
	 *
	 * @since 0.5.2
	 */
	public static function categoryIcon()
	{
		return "<i class=\"fas fa-flag\" aria-hidden=\"true\" title=\"" . Text::_('COM_SDAJEM_ICON_CATEGORY') . "\">&nbsp;</i>";
	}

	/**
	 * Users icon
	 *
	 * @return string
	 *
	 * @since 0.5.2
	 */
	public static function usersIcon()
	{
		return "<i class=\"fas fa-users\" aria-hidden=\"true\" title=\"" . Text::_('COM_SDAJEM_ICON_USERS') . "\">&nbsp;</i>";
	}

	/**
	 *
	 * @return string
	 *
	 * @since 0.5.2
	 */
	public static function statusIcon()
	{
		return "<i class=\"fas fa-info-circle\" aria-hidden=\"true\" title=\"" . Text::_('COM_SDAJEM_ICON_STATUS') . "\">&nbsp;</i>";
	}

	/**
	 *
	 * @return string
	 *
	 * @since 0.5.2
	 */
	public static function commentsIcon()
	{
		return "<span class=\"icon-comments-2\" aria-hidden=\"true\"></span>";
	}

	/**
	 *
	 * @return string
	 *
	 * @since 0.5.2
	 */
	public static function planingIcon()
	{
		return "<i class=\"fas fa-pencil-ruler\" aria-hidden='true'></i>";
	}
}