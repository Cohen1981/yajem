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
enum IntAttStatusEnum: int
{
	case NA = 0;
	case POSITIVE = 1;
	case NEGATIVE = 2;
	case GUEST = 3;

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
			self::POSITIVE => 'COM_SDAJEM_POSITIVE',
			self::NEGATIVE => 'COM_SDAJEM_NEGATIVE',
			self::GUEST => 'COM_SDAJEM_GUEST'
		};
	}

	public function getStatusBadge(): string
	{
		return match($this)
		{
			self::NA => '<span class="badge color-neutral">' . Text::_('COM_SDAJEM_INTEREST_NA') . '</span>',
			self::POSITIVE => '<span class="badge color-ok">' . Text::_('COM_SDAJEM_POSITIVE') . '</span>',
			self::NEGATIVE => '<span class="badge color-nok">' . Text::_('COM_SDAJEM_NEGATIVE') . '</span>',
			self::GUEST => '<span class="badge color-neutral">' . Text::_('COM_SDAJEM_GUEST') . '</span>'
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
			self::POSITIVE => 'COM_SDAJEM_POSITIVE',
			self::NEGATIVE => 'COM_SDAJEM_NEGATIVE',
			self::GUEST => 'COM_SDAJEM_GUEST'
		};
	}

	public function getAttendingButtonLabel(): string
	{
		return match($this)
		{
			self::NA => 'COM_SDAJEM_ATTENDING_NA',
			self::POSITIVE => 'COM_SDAJEM_ATTENDING_ATTEND',
			self::NEGATIVE => 'COM_SDAJEM_ATTENDING_NOT_ATTENDING',
			self::GUEST => 'COM_SDAJEM_GUEST'
		};
	}

	public function getAttendingStatusBadge(): string
	{
		return match($this)
		{
			self::NA => '<span class="badge color-neutral">' . Text::_('COM_SDAJEM_ATTENDING_NA') . '</span>',
			self::POSITIVE => '<span class="badge color-ok">' . Text::_('COM_SDAJEM_ATTENDING_ATTEND') . '</span>',
			self::NEGATIVE => '<span class="badge color-nok">' . Text::_('COM_SDAJEM_ATTENDING_NOT_ATTENDING') . '</span>',
			self::GUEST => '<span class="badge color-neutral">' . Text::_('COM_SDAJEM_GUEST') . '</span>'
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
	public function getAttendingStatusLabel(): string
	{
		return match($this)
		{
			self::NA => 'COM_SDAJEM_ATTENDING_STATUS_NA',
			self::POSITIVE => 'COM_SDAJEM_ATTENDING_STATUS_ATTENDING',
			self::NEGATIVE => 'COM_SDAJEM_ATTENDING_STATUS_NOT_ATTENDING',
			self::GUEST => 'COM_SDAJEM_GUEST'
		};
	}

	public function getInterestButtonLabel(): string
	{
		return match($this)
		{
			self::NA => 'COM_SDAJEM_INTEREST_NA',
			self::POSITIVE => 'COM_SDAJEM_INTERESTED',
			self::NEGATIVE => 'COM_SDAJEM_NOT_INTERESTED',
			self::GUEST => 'COM_SDAJEM_INTERESTED_GUEST'
		};
	}

	public function getInterestStatusBadge(): string
	{
		return match($this)
		{
			self::NA => '<span class="badge color-neutral">' . Text::_('COM_SDAJEM_INTEREST_NA') . '</span>',
			self::POSITIVE => '<span class="badge color-ok">' . Text::_('COM_SDAJEM_INTERESTED') . '</span>',
			self::NEGATIVE => '<span class="badge color-nok">' . Text::_('COM_SDAJEM_NOT_INTERESTED') . '</span>',
			self::GUEST => '<span class="badge color-neutral">' . Text::_('COM_SDAJEM_GUEST') . '</span>'
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
	public function getInterestStatusLabel(): string
	{
		return match($this)
		{
			self::NA => 'COM_SDAJEM_INTEREST_NA',
			self::POSITIVE => 'COM_SDAJEM_INTERESTED',
			self::NEGATIVE => 'COM_SDAJEM_NOT_INTERESTED',
			self::GUEST => 'COM_SDAJEM_GUEST'
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
			self::NA => 'COM_SDAJEM_POSITIVE_NA',
			self::POSITIVE => 'event.positive',
			self::NEGATIVE => 'event.negative',
			self::GUEST => 'event.guest'
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
			self::POSITIVE => 'fas fa-thumbs-up',
			self::NEGATIVE => 'fas fa-thumbs-down',
			self::GUEST => 'fas fa-hand-point-up'
		};
	}

	public function getButtonClass(): string
	{
		return match($this)
		{
			self::NA => 'fas fa-question',
			self::POSITIVE => 'btn-success',
			self::NEGATIVE => 'btn-danger',
			self::GUEST => 'btn-secondary'
		};
	}

}