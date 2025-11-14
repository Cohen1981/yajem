<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Sda\Component\Sdajem\Site\Model\Item;

use Sda\Component\Sdajem\Administrator\Trait\ItemTrait;

/**
 * @since       1.4.0
 * @package     Sda\Component\Sdajem\Site\Model\Item
 *
 * For programming convenience, the class gives type hinting for the class properties.
 */
class Location extends \stdClass
{
	use ItemTrait;

	public ?string $title;
	public ?string $description;
	public ?string $url;
	public ?string $street;
	public ?string $postalCode;
	public ?string $city;
	public ?string $stateAddress;
	public ?string $country;
	public ?string $latlng;
	public ?int $contactId;
	public ?string $image;
	public ?int $catid;

	public ?string $typeAlias;
	public ?int $id;
	public ?int $access;
	public ?string $alias;
	public ?string $created;
	public ?int $created_by;
	public ?string $created_by_alias;
	public ?int $published;
	public ?string  $publish_up;
	public ?string $publish_down;
	public ?int $state;
	public ?int $ordering;
	public ?string $language;

}