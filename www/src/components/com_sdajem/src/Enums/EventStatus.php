<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Enums;

enum EventStatus: int
{
	case NA = 0;
	case ATTENDING = 1;
	case NOT_ATTENDING = 2;

	public function color(): string
	{
		return match($this)
		{
			self::NA => 'grey',
			self::ATTENDING => 'green',
			self::NOT_ATTENDING => 'red'
		};
	}
}