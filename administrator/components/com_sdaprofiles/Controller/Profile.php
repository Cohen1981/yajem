<?php
/**
 * @package     Sda\Profiles\Admin\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Profiles\Admin\Controller;

use FOF30\Controller\DataController;
use Joomla\CMS\Factory;

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
		if (!$this->input->get('avatar'))
		{
			$this->input->set('avatar', 'media/com_sdaprofiles/images/user-image-blanco.png');
		}
	}
}
