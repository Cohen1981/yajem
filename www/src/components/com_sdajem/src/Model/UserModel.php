<?php
/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\User\User;
use Joomla\CMS\User\UserFactoryInterface;
use Joomla\CMS\User\UserHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Component\Users\Site\Model\ProfileModel;

/**
 * @since       __BUMP_VERSION__
 * @package     Sda\Component\Sdajem\Site\Model
 *
 * @property int            id
 * @property User           user
 * @property ProfileModel   profile
 * @property array          userData
 */
class UserModel
{
	public function __construct(int $userId)
	{
		if (isset($userId)) {
			$this->user = Factory::getContainer()->get(UserFactoryInterface::class)->loadUserById($userId);

			$this->profile = UserHelper::getProfile($userId)->get('profile');

			$userdata = FieldsHelper::getFields('com_users.user', $this->user, true);
			$tmp          = isset($userdata) ? $userdata : array();
			$customFields = array();

			foreach ($tmp as $customField)
			{
				$customFields[$customField->name] = $customField;
			}
			$this->userData = $customFields;
		}
	}
}