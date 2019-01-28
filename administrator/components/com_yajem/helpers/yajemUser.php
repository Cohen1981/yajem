<?php
/**
 * @package    yajem
 *
 * @author     abahl <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\Factory;
use Joomla\CMS\User\UserHelper;

defined('_JEXEC') or die;

/**
 * Yajem helper.
 *
 * @package     A package name
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
	public function getUserList() {
		$db     = Factory::getDbo();
		$query  = $db->getQuery(true);

		$query->select('id')->from('#__users');

		$userIds = $db->setQuery($query)->loadColumn();

		foreach ($userIds as $userId) {
			$profiles[$userId] = YajemUserHelper::getUser($userId);
		}

		return $profiles;
	}

	/**
	 * @param $userId
	 *
	 * @return mixed
	 *
	 * @since 1.2.0
	 */
	public function getUser($userId) {

		$PROFILEKEYS = array("id", "name", "username", "email", "avatar");

		$user = (array) Factory::getUser( $userId );
		foreach ($user as $k => $v) {
			if (in_array($k, $PROFILEKEYS, true))
			{
				$profile[$k] = $user[$k] = $v;
			}
		}

		$userProfile = (array) UserHelper::getProfile( $userId );
		foreach ($userProfile['profile'] as $k => $v) {
			if (in_array($k, $PROFILEKEYS, true))
			{
				$profile[$k] = $userProfile['profile'][$k] = $v;
			}
		}

		foreach ($userProfile['profileYajem'] as $k => $v) {
			if (in_array($k, $PROFILEKEYS, true))
			{
				$profile[$k] = $userProfile['profileYajem'][$k] = $v;
			}
		}

		if (!$profile['name']) {
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
