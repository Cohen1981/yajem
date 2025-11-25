<?php
/**
 * @copyright (c) 2025 Alexander Bahlo <abahlo@hotmail.de>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sda\Component\Sdajem\Administrator\Library\Item;

use Sda\Component\Sdajem\Administrator\Library\Interface\ItemInterface;
use Sda\Component\Sdajem\Administrator\Library\Trait\ItemTrait;
use stdClass;

/**
 * @package     Sda\Component\Sdajem\Administrator\Model\Item
 * @since       1.5.3
 * Representation of the database table #__sdajem_events.
 * All field types are database-compatible.
 */
class FittingTableItem extends stdClass implements ItemInterface
{
	use ItemTrait;

	/**
	 * @var integer|null
	 * @since 1.5.3
	 * The primary Key of the table
	 */
	public ?int $id;

	/**
	 * @var integer|null
	 * @since 1.5.3
	 * Stores the joomla access level
	 */
	public ?int $access;

	/**
	 * @var string|null
	 * @since 1.5.3
	 * The joomla alias for an item
	 */
	public ?string $alias;

	/**
	 * @var integer|null
	 * @since 1.5.3
	 * Represents the state or status of an entity.
	 */
	public ?int $state;

	/**
	 * @var integer|null
	 * @since 1.5.3
	 * Specifies the ordering of items.
	 */
	public ?int $ordering;

	/**
	 * @var string|null
	 * Represents the title or name of an entity.
	 * @since 1.5.3
	 */
	public ?string $title;

	/**
	 * @var string|null
	 * Holds the description text
	 * @since 1.5.3
	 */
	public ?string $description;

	/**
	 * @var float|null
	 * Represents the length value, which can be null if not set.
	 * @since 1.5.3
	 */
	public ?float $length;

	/**
	 * @var float|null
	 * Represents the width of a given element or component.
	 * @since 1.5.3
	 */
	public ?float $width;

	/**
	 * @var integer|null
	 * Marks the item as standard fitting.
	 * @since 1.5.3
	 */
	public ?int $standard;

	/**
	 * @var integer|null
	 * Defines the type of fitting for an item.
	 * @since 1.5.3
	 */
	public ?int $fittingType;

	/**
	 * @var integer|null
	 * Represents the unique identifier for a user.
	 * @since 1.5.3
	 */
	public ?int $user_id;

	/**
	 * @var string|null
	 * Stores the image data or identifier.
	 * @since 1.5.3
	 */
	public ?string $image;

	/**
	 * @var integer|null
	 * Indicates if space is required.
	 * @since 1.5.3
	 */
	public ?int $needSpace;
}
