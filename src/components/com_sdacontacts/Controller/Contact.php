<?php
/**
 * @package     Sda\Contacts\Site\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Contacts\Site\Controller;

use FOF30\Controller\DataController;
use Sda\Contacts\Admin\Helper\RefererHelper;

/**
 * @package     Sda\Contacts\Controller
 *
 * @since       0.0.2
 */
class Contact extends DataController
{
	/**
	 *
	 * @return void
	 * @since 0.0.2
	 */
	protected function onBeforeAdd()
	{
		$this->defaultsForAdd['image'] = 'media/com_sdacontacts/images/noimage.png';
	}

	/**
	 *
	 * @return void
	 * @since 0.0.2
	 */
	protected function onBeforeSave()
	{
		if (!$this->input->get('image'))
		{
			$this->input->set('image', 'media/com_sdacontacts/images/noimage.png');
		}
	}

	/**
	 * @return void
	 *
	 * @since 0.7.0
	 */
	public function onAfterSave() : void
	{
		$this->setRedirect(RefererHelper::getReferer());
	}

	/**
	 * @return void
	 *
	 * @since 0.7.0
	 */
	public function onAfterCancel()
	{
		$this->setRedirect(RefererHelper::getReferer());
	}
}