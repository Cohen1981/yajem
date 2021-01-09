<?php

/**
 * @version 1.0.0
 * @package SDA
 * @subpackage Sda Mailer Plugin
 * @copyright (C) 2018 Alexander Bahlo
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;

use FOF30\Container\Container;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Sda\Profiles\Site\Model\Profile;

/**
 * Send Mail at defined events.
 *
 * @package     Sda
 *
 * @since       1.0
 */
class plgUserSdaprofile extends CMSPlugin
{
	public function __construct($subject, $config)
	{
		parent::__construct($subject, $config);

		if (!defined('FOF30_INCLUDED') && !@include_once JPATH_LIBRARIES . '/fof30/include.php')
		{
			throw new RuntimeException('FOF 3.0 is not installed', 500);
		}
	}

	function onUserAfterSave($user, $isNew, $success, $msg)
	{
		if ($isNew)
		{
			/** @var Profile $profile */
			$profile = Container::getInstance('com_sdaprofiles')->factory->model('Profile');
			$profile->users_user_id = $user['id'];
			$profile->userName      = $user['username'];
			$profile->avatar        = 'media/com_sdaprofiles/images/user-image-blanco.png';
			$profile->groupProfile  = 0;
			$profile->save();
		}
	}
}