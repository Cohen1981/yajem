<?php
/**
 * @package     Sda\Component\Sdajem\Site\Enums
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Enums;

enum EventStatusEnum : int
{
	case OPEN = 0;
	case APPLIED = 1;
	case CONFIRMED = 2;
	case CANCELED = 3;

	/**
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 *
	 * Joomla translate String for event status label
	 */
	public function getStatusLabel(): string
	{
		return match($this)
		{
			self::OPEN => 'COM_SDAJEM_EVENT_STATUS_OPEN',
			self::APPLIED => 'COM_SDAJEM_EVENT_STATUS_APPLIED',
			self::CONFIRMED => 'COM_SDAJEM_EVENT_STATUS_CONFIRMED',
			self::CANCELED => 'COM_SDAJEM_EVENT_STATUS_CANCELED'
		};
	}

	/**
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 *
	 * Actions for event status changes. Task to get status ATTENDING would be 'attending.attend'
	 */
	public function getEventAction(): string
	{
		return match($this)
		{
			self::OPEN => 'event.open',
			self::APPLIED => 'event.applied',
			self::CONFIRMED => 'event.confirmed',
			self::CANCELED => 'event.canceled'
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
			self::OPEN => 'fas fa-question',
			self::APPLIED => 'fas fa-clipboard',
			self::CONFIRMED => 'fas fa-thumbs-up',
			self::CANCELED => 'fas fa-thumbs-down'
		};
	}

	public function getColor(): string
	{
		return match($this)
		{
			self::OPEN => '#c7cfd2',
			self::APPLIED => '#F9eb53',
			self::CONFIRMED => '#4db942',
			self::CANCELED => '#Bd181b'
		};
	}
}