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
	case CONFIRMED = 1;
	case CANCELED = 2;
}