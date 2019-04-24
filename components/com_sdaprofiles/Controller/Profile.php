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
	 * @param   boolean $newGroup New Group ?
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeAdd(bool $newGroup = false)
	{
		if (!((int) $this->defaultsForAdd['groupProfile'] === 1))
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
				$profile                = $container->factory->model('Profile');
				$profile->users_user_id = Factory::getUser()->id;
				$profile->userName      = Factory::getUser()->username;
				$profile->avatar        = 'media/com_sdaprofiles/images/user-image-blanco.png';
				$profile->groupProfile  = 0;
				$profile->save();
				$this->setRedirect('index.php?option=com_sdaprofiles&task=edit&id=' . $profile->sdaprofiles_profile_id);
			}

			$this->redirect();
		}
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeSave()
	{
		parent::onBeforeSave();

		if (!(bool) $this->input->get('groupProfile'))
		{
			$this->input->set('users_user_id', Factory::getUser()->id);
			$this->input->set('userName', Factory::getUser()->username);
			$this->input->set('groupProfile', 0);
		}
		else
		{
			$this->input->set('users_user_id', null);
			$this->input->set('userName', $this->input->get('userName'));
			$this->input->set('groupProfile', 1);
		}

	}

	/**
	 * @return void
	 *
	 * @since 0.1.4
	 */
	public function newGroup()
	{
		$this->defaultsForAdd['users_user_id'] = '';
		$this->defaultsForAdd['groupProfile'] = 1;
		$this->defaultsForAdd['mailOnNew'] = 0;
		$this->defaultsForAdd['mailOnEdited'] = 0;
		$this->execute('add');
	}

	/**
	 * @return void
	 *
	 * @since 0.1.4
	 */
	public function editGroup()
	{
		if ($this->input->get('profileId'))
		{
			$this->setRedirect('index.php?option=com_sdaprofiles&task=edit&id=' . (int) $this->input->get('profileId'));
		}
	}
}