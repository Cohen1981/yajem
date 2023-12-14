<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Enums;

use Joomla\CMS\Language\Text;

/**
 * @since       1.0.0
 * @package     Sda\Component\Sdajem\Site\Enums
 *
 * Event Attending Status
 */
enum InterestStatusEnum: int
{
	case NA = 0;
	case INTERESTED = 1;
	case NOT_INTERESTED = 2;

	/**
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 *
	 * joomla translate String for Buttons
	 */
	public function getButtonLabel(): string
	{
		return match($this)
		{
			self::NA => 'COM_SDAJEM_INTEREST_NA',
			self::INTERESTED => 'COM_SDAJEM_INTERESTED',
			self::NOT_INTERESTED => 'COM_SDAJEM_NOT_INTERESTED'
		};
	}

	public function getStatusBadge(): string
	{
		return match($this)
		{
			self::NA => '<span class="badge color-neutral">' . Text::_('COM_SDAJEM_INTEREST_NA') . '</span>',
			self::INTERESTED => '<span class="badge color-ok">' . Text::_('COM_SDAJEM_INTERESTED') . '</span>',
			self::NOT_INTERESTED => '<span class="badge color-nok">' . Text::_('COM_SDAJEM_NOT_INTERESTED') . '</span>'
		};
	}

	/**
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 *
	 * Joomla translate String for attening status label
	 */
	public function getStatusLabel(): string
	{
		return match($this)
		{
			self::NA => 'COM_SDAJEM_INTEREST_NA',
			self::INTERESTED => 'COM_SDAJEM_INTERESTED',
			self::NOT_INTERESTED => 'COM_SDAJEM_NOT_INTERESTED'
		};
	}

	/**
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 *
	 * Actions for attending status changes. Task to get status ATTENDING would be 'attending.attend'
	 */
	public function getAction(): string
	{
		return match($this)
		{
			self::NA => 'COM_SDAJEM_INTERESTED_NA',
			self::INTERESTED => 'interest.interested',
			self::NOT_INTERESTED => 'interest.notinterested'
		};
	}

	/**
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 *
	 * get the fontawesome class for a status symbol
	 */
	public function getIcon(): string
	{
		return match($this)
		{
			self::NA => 'fas fa-question',
			self::INTERESTED => 'fas fa-thumbs-up',
			self::NOT_INTERESTED => 'fas fa-thumbs-down'
		};
	}

	public function getButtonClass(): string
	{
		return match($this)
		{
			self::NA => 'fas fa-question',
			self::INTERESTED => 'btn-success',
			self::NOT_INTERESTED => 'btn-danger'
		};
	}

}