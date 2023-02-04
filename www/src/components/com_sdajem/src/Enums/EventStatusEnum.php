<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Enums;

enum EventStatusEnum: int
{
	case NA = 0;
	case ATTENDING = 1;
	case NOT_ATTENDING = 2;

	public function getButtonLabel(): string
	{
		return match($this)
		{
			self::NA => 'COM_SDAJEM_ATTENDING_NA',
			self::ATTENDING => 'COM_SDAJEM_ATTENDING_ATTEND',
			self::NOT_ATTENDING => 'COM_SDAJEM_ATTENDING_NOT_ATTENDING'
		};
	}

	public function getStatusLabel(): string
	{
		return match($this)
		{
			self::NA => 'COM_SDAJEM_ATTENDING_STATUS_NA',
			self::ATTENDING => 'COM_SDAJEM_ATTENDING_STATUS_ATTENDING',
			self::NOT_ATTENDING => 'COM_SDAJEM_ATTENDING_STATUS_NOT_ATTENDING'
		};
	}

}