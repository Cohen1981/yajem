<?php
/**
 * @package     Joomla\Component\Yajem\Administrator\Classes
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Yajem\User;

use Joomla\CMS\Factory;

/**
 * @package     Joomla\Component\Yajem\Administrator\Classes
 *
 * @since       1.0.0
 */
class YajemUserProfiles
{
	/**
	 * @var array
	 * @since 1.0.0
	 */
	private $profiles;
	/**
	 * YajemUserProfiles constructor.
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$db     = Factory::getDbo();
		$query  = $db->getQuery(true);

		$query->select('id')->from('#__users');

		$userIds = $db->setQuery($query)->loadColumn();

		foreach ($userIds as $userId)
		{
			$this->profiles[$userId] = new YajemUserProfile($userId);
		}
	}

	/**
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public function getProfiles()
	{
		return $this->profiles;
	}

	/**
	 * @param   int $userId User id
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	public function getProfile(int $userId)
	{
		return $this->profiles[$userId];
	}
}