<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Library\Item
 * @subpackage  com_sdajem
 * @copyright   (C)) 2025 Survivants-d-Acre <https://www.survivants-d-acre.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.5.3
 */
namespace Sda\Component\Sdajem\Administrator\Library\Item;

use Sda\Component\Sdajem\Administrator\Library\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Administrator\Library\Enums\IntAttStatusEnum;
use Sda\Component\Sdajem\Administrator\Library\Trait\ItemTrait;

/**
 * @package     Sda\Component\Sdajem\Administrator\Library\Item
 *
 * @since       version 1.5.3
 */
class Fitting extends FittingTableItem
{
	use ItemTrait;

	/**
	 * Stores the username of a user.
	 * @var string|null
	 * @since 1.5.3
	 */
	public ?string $userName;
}
