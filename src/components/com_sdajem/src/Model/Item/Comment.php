<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Sda\Component\Sdajem\Site\Model\Item;

use Joomla\CMS\Factory;
use Joomla\CMS\User\User;
use Joomla\CMS\User\UserFactoryInterface;
use Joomla\Registry\Registry;
use ReflectionObject;
use Sda\Component\Sdajem\Administrator\Trait\ItemTrait;
use Sda\Component\Sdajem\Site\Model\UserModel;

/**
 * @since       1.4.0
 * @package     Sda\Component\Sdajem\Site\Model\Item
 *
 * For programming convenience, the class gives type hinting for the class properties.
 */
class Comment extends \stdClass
{
	use ItemTrait;

	public ?int $id;
	public ?int $users_user_id;
	public ?int $sdajem_event_id;
	public ?string $comment;
	public ?string $timestamp;
	public ?Registry $commentReadBy;
	public ?UserModel $commentUser;

	public function __construct()
	{
		$selfReflection = new ReflectionObject($this);
		foreach ($selfReflection->getProperties() as $property)
		{
			$name = $property->getName();
			$this->$name = null;
		}
	}

	public static function createFromArray(array $data): self
	{
		$item = new self();
		$selfReflection = new ReflectionObject($item);
		foreach ($data as $key => $value)
		{
			if ($selfReflection->hasProperty($key)) {
				$item->$key = $value;
			}
		}

		if (isset($item->users_user_id))
			$item->commentUser = New UserModel($item->users_user_id);

		return $item;
	}
}