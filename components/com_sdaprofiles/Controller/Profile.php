<?php
/**
 * @package     Sda\Profiles\Admin\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Profiles\Site\Controller;

use Sda\Profiles\Admin\Controller\Profile as AdminProfile;
use Joomla\CMS\Factory;

/**
 * @package     Sda\Profiles\Site\Controller
 *
 * @since       0.0.1
 */
class Profile extends AdminProfile
{
	/**
	 * If User adds his profile in frontend we make sure, that his user is linked.
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeAdd()
	{
		parent::onBeforeAdd();
		$this->defaultsForAdd['users_user_id'] = Factory::getUser()->id;
		$this->defaultsForAdd['userName'] = Factory::getUser()->username;
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeSave()
	{
		parent::onBeforeSave();
		$this->input->set('users_user_id', Factory::getUser()->id);
		$this->input->set('userName', Factory::getUser()->username);
	}
}