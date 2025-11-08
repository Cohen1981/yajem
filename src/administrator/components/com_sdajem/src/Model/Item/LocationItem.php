<?php

namespace Sda\Component\Sdajem\Administrator\Model\Item;

use Sda\Component\Sdajem\Administrator\Trait\ItemTrait;

/**
 * @since       1.4.0
 * @package     Sda\Component\Sdajem\Administrator\Model\Item
 *
 * For programming convenience, the class gives type hinting for the class properties.
 */
class LocationItem extends \stdClass
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

}