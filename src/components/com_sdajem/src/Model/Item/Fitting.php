<?php
/**
 * @copyright (c) 2025 Alexander Bahlo <abahlo@hotmail.de>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sda\Component\Sdajem\Site\Model\Item;

use Sda\Component\Sdajem\Administrator\Library\Trait\ItemTrait;

/**
 * @package     Sda\Component\Sdajem\Site\Model\Item
 * For programming convenience, the class gives type hinting for the class properties.
 * @since       1.5.2
 */
class Fitting extends \stdClass
{
	use ItemTrait;

	public ?int $id;
	public ?int $access;
	public ?string $alias;
	public ?int $state;
	public ?int $ordering;
	public ?string $title;
	public ?string $description;
	public ?float $length;
	public ?float $width;
	public ?int $standard;
	public ?int $fittiingType;
	public ?int $userId;
	public ?string $image;
	public ?int $needSpace;
}
