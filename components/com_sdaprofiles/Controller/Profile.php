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
use FOF30\Container\Container;
use Sda\Profiles\Site\Model\User;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

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
		$currentUser = Factory::getUser()->id;

		$container = Container::getInstance('com_sdaprofiles');

		/** @var User $user */
		$user = $container->factory->model('User');

		$user->load((int) $currentUser);

		if ($user->profile)
		{
			$this->setRedirect('index.php?option=com_sdaprofiles&task=edit&id=' . $user->profile->sdaprofiles_profile_id);
		}
		else
		{
			/** @var \Sda\Profiles\Site\Model\Profile $profile */
			$profile = $container->factory->model('Profile');
			$profile->users_user_id = Factory::getUser()->id;
			$profile->userName = Factory::getUser()->username;
			$profile->avatar = 'media/com_sdaprofiles/images/user-image-blanco.png';
			$profile->save();
			$this->setRedirect('index.php?option=com_sdaprofiles&task=edit&id=' . $profile->sdaprofiles_profile_id);
		}

		$this->redirect();
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