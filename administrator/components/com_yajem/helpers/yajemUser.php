<?php
/**
 * @package     Joomla\Component\Yajem\Administrator\Helpers
 *
 * @copyright   2018 Alexander Bahlo
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Factory;
use Joomla\CMS\User\UserHelper;

defined('_JEXEC') or die;

/**
 * Yajem helper.
 *
 * @package     Joomla\Component\Yajem\Administrator\Helpers
 * @since       1.2
 */
class YajemUserHelperAdmin
{
	/**
	 *
	 * @return mixed
	 *
	 * @since 1.2
	 */
	public function getUserList()
	{
		$db     = Factory::getDbo();
		$query  = $db->getQuery(true);

		$query->select('id')->from('#__users');

		$userIds = $db->setQuery($query)->loadColumn();

		foreach ($userIds as $userId)
		{
			$profiles[$userId] = YajemUserHelper::getUser($userId);
		}

		return $profiles;
	}

	/**
	 * @param   int $userId User Id
	 *
	 * @return mixed
	 *
	 * @since 1.2.0
	 */
	public function getUser($userId)
	{
		$PROFILEKEYS = array("id", "name", "username", "email", "avatar");

		$user = (array) Factory::getUser($userId);

		foreach ($user as $k => $v)
		{
			if (in_array($k, $PROFILEKEYS, true))
			{
				$profile[$k] = $user[$k] = $v;
			}
		}

		$userProfile = (array) UserHelper::getProfile($userId);

		foreach ($userProfile['profile'] as $k => $v)
		{
			if (in_array($k, $PROFILEKEYS, true))
			{
				$profile[$k] = $userProfile['profile'][$k] = $v;
			}
		}

		foreach ($userProfile['profileYajem'] as $k => $v)
		{
			if (in_array($k, $PROFILEKEYS, true))
			{
				$profile[$k] = $userProfile['profileYajem'][$k] = $v;
			}
		}

		if (!$profile['name'])
		{
			$profile['name'] = $profile['username'];
		}

		return $profile;
	}
}
/**
 * Joomla's way to add SubMenus in categoris view
 * @package     Yajem
 *
 * @since       version
 */
class YajemUserHelper extends YajemUserHelperAdmin
{
}
