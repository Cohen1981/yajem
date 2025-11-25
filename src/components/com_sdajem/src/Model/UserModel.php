<?php 

/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\User\User;
use Joomla\CMS\User\UserFactoryInterface;
use Joomla\CMS\User\UserHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

/**
 * @since       1.0.0
 * @package     Sda\Component\Sdajem\Site\Model
*/
class UserModel
{
	protected ?int $id = null;
	public ?array $userData = [];
	public ?array $profile = [];
	public ?User $user = null;

	public function __construct(int $userId = null)
	{
		if (isset($userId)) {
			$this->user = Factory::getContainer()->get(UserFactoryInterface::class)->loadUserById($userId);

			if (!empty(UserHelper::getProfile($userId)->profile))
				$this->profile = UserHelper::getProfile($userId)->profile;

			$userdata = FieldsHelper::getFields('com_users.user', $this->user, true);
			$tmp          = $userdata ?? array();
			$customFields = array();

			foreach ($tmp as $customField)
			{
				$customFields[$customField->name] = $customField;
			}
			$this->userData = $customFields;
		}
	}
}