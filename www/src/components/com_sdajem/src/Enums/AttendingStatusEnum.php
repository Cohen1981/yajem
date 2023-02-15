<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Enums;

/**
 * @since       1.0.0
 * @package     Sda\Component\Sdajem\Site\Enums
 *
 * Event Attending Status
 */
enum AttendingStatusEnum: int
{
	case NA = 0;
	case ATTENDING = 1;
	case NOT_ATTENDING = 2;

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
			self::NA => 'COM_SDAJEM_ATTENDING_NA',
			self::ATTENDING => 'COM_SDAJEM_ATTENDING_ATTEND',
			self::NOT_ATTENDING => 'COM_SDAJEM_ATTENDING_NOT_ATTENDING'
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
			self::NA => 'COM_SDAJEM_ATTENDING_STATUS_NA',
			self::ATTENDING => 'COM_SDAJEM_ATTENDING_STATUS_ATTENDING',
			self::NOT_ATTENDING => 'COM_SDAJEM_ATTENDING_STATUS_NOT_ATTENDING'
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
			self::NA => 'COM_SDAJEM_ATTENDING_STATUS_NA',
			self::ATTENDING => 'attending.attend',
			self::NOT_ATTENDING => 'attending.unattend'
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
			self::ATTENDING => 'fas fa-thumbs-up',
			self::NOT_ATTENDING => 'fas fa-thumbs-down'
		};
	}

	public function getButtonClass(): string
	{
		return match($this)
		{
			self::NA => 'fas fa-question',
			self::ATTENDING => 'btn-success',
			self::NOT_ATTENDING => 'btn-danger'
		};
	}

}