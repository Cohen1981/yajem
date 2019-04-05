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
use FOF30\Utils\ArrayHelper;

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
		$referer = null;

		try
		{
			$refererArray = Factory::getApplication()->getUserState('referer');

			if ($refererArray)
			{
				$referer = array_pop($refererArray);
				Factory::getApplication()->setUserState('referer', $refererArray);
			}
		}
		catch (\Exception $e)
		{
			return null;
		}

		return $referer;
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
		/** @var array $refererArray */
		try
		{
			$refererArray = Factory::getApplication()->getUserState('referer');
		}
		catch (\Exception $e)
		{
		}

		if ($refererArray && is_array($refererArray) && end($refererArray) != $referer)
		{
			array_push($refererArray, $referer);
		}
		else
		{
			$refererArray = array($referer);
		}

		try
		{
			Factory::getApplication()->setUserState('referer', $refererArray);
		}
		catch (\Exception $e)
		{
		}
	}
}