<?php
/**
 * @package     Sda\Jem\Site\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Site\Controller;

use FOF30\Controller\DataController;
use Joomla\CMS\Factory;

/**
 * @package     Sda\Jem\Site\Controller
 *
 * @since       0.0.1
 */
class Location extends DataController
{
	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeEdit() : void
	{
		$this->setReferer();
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onAfterSave() : void
	{
		$this->setRedirect($this->getReferer());
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onAfterCancel()
	{
		$this->setRedirect($this->getReferer());
	}

	/**
	 *
	 * @return string The URL to redirect to
	 *
	 * @since 0.0.1
	 */
	private function getReferer() : string
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
	 * @return void
	 * @since 0.0.1
	 */
	private function setReferer()
	{
		$referer = $this->input->server->getString('HTTP_REFERER');

		try
		{
			Factory::getApplication()->setUserState('referer', $referer);
		}
		catch (\Exception $e)
		{
		}
	}
}