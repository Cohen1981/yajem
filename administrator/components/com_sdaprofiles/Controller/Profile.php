<?php
/**
 * @package     Sda\Profiles\Admin\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Profiles\Admin\Controller;

use FOF30\Container\Container;
use FOF30\Controller\DataController;
use Joomla\CMS\Factory;
use Sda\Profiles\Admin\Model\User;

class Profile extends DataController
{
	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeAdd()
	{
		$this->defaultsForAdd['avatar'] = 'media/com_sdaprofiles/images/user-image-blanco.png';
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeSave()
	{
		$this->defaults();
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeApply()
	{
		$this->defaults();
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	private function defaults()
	{
		if (!$this->input->get('avatar'))
		{
			$this->input->set('avatar', 'media/com_sdaprofiles/images/user-image-blanco.png');
		}

		if ($this->input->get('users_user_id'))
		{
			$id = $this->input->get('users_user_id');

			$container = Container::getInstance('com_sdaprofiles');

			/** @var \Sda\Profiles\Site\Model\User $user */
			$user = $container->factory->model('User');

			$user->load((int) $id);

			$this->input->set('userName', $user->username);
		}
	}
}
