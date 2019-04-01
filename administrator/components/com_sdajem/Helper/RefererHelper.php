<?php
/**
 * @package     Sda\Jem\Admin\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Admin\Helper;

use Joomla\CMS\Factory;

/**
 * @package     Sda\Jem\Admin\Helper
 *
 * @since       0.0.1
 */
class RefererHelper
{
	/**
	 *
	 * @return string The URL to redirect to
	 *
	 * @since 0.0.1
	 */
	public static function getReferer() : string
	{
		try
		{
			return Factory::getApplication()->getUserState('referer');
		}
		catch (\Exception $e)
		{
			return null;
		}
	}

	/**
	 * Stores the URL of the calling page in the User Session
	 *
	 * @param   string $referer The referer URL
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public static function setReferer(string $referer)
	{
		try
		{
			Factory::getApplication()->setUserState('referer', $referer);
		}
		catch (\Exception $e)
		{
		}
	}
}