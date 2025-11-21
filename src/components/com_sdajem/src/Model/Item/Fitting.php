<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Sda\Component\Sdajem\Site\Model\Item;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;
use Sda\Component\Sdajem\Administrator\Trait\ItemTrait;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Enums\IntAttStatusEnum;
use stdClass;

/**
 * @since       1.5.2
 * @package     Sda\Component\Sdajem\Site\Model\Item
 *
 * For programming convenience, the class gives type hinting for the class properties.
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