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
use Sda\Profiles\Admin\Model\Profile as ProfileModel;

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
	 * @param bool $newGroup New Group ?
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeAdd($newGroup = false)
	{
		// Make sure we have a user or a group profile
		if (!((int) $this->defaultsForAdd['groupProfile'] === 1) && $this->defaultsForAdd['users_user_id'])
		{
			if ($this->defaultsForAdd['users_user_id'])
			{
				// we have a user id, so lets load the user
				$currentUser = $this->defaultsForAdd['users_user_id'];

				$container = Container::getInstance('com_sdaprofiles');

				/** @var User $user */
				$user = $container->factory->model('User');

				$user->load((int) $currentUser);

				// if we already have a profile just redirect to the edit form
				if ($user->profile)
				{
					$this->setRedirect('index.php?option=com_sdaprofiles&task=edit&id=' . $user->profile->sdaprofiles_profile_id);
				}
				// if we don't have a profile we create it first before redirecting to edit.
				// this way users are able to fill in all information
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
			}

			$this->redirect();
		}
	}

	public function ownProfile()
	{
		$this->defaultsForAdd['users_user_id'] = Factory::getUser()->id;;
		$this->execute('add');
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeSave()
	{
		parent::onBeforeSave();

		if (!$this->input->get('users_user_id') && !$this->input->get('sdaprofiles_profile_id') && !((bool) $this->input->get('groupProfile')))
		{
			$this->input->set('users_user_id', Factory::getUser()->id);
			$this->input->set('userName', Factory::getUser()->username);
			$this->input->set('groupProfile', 0);
		}

		if ((bool) $this->input->get('groupProfile'))
		{
			$this->input->set('users_user_id', null);
			$this->input->set('userName', $this->input->get('userName'));
			$this->input->set('groupProfile', 1);
			$this->input->set('dob', null);
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
	public function editProfile()
	{
		if ($this->input->get('layout') == 'ownProfile') {
			$profileId = ProfileModel::getProfileIdForUserId(Factory::getUser()->id);
		} else {
			$profileId = $this->input->get('profileId');
		}
		$this->setRedirect('index.php?option=com_sdaprofiles&task=edit&id=' . (int) $profileId);
	}
}