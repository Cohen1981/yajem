<?php
/**
 * @package     Sda\Profiles\Site\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Profiles\Site\Controller;

use FOF30\Controller\DataController;
use Joomla\CMS\Factory;
use FOF30\Container\Container;
use Joomla\CMS\Language\Text;

/**
 * @package     Sda\Profiles\Site\Controller
 *
 * @since       0.0.1
 */
class Fitting extends DataController
{
	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeAdd()
	{
		$this->defaultsForAdd['sdaprofiles_profile_id'] = $this->input->get('profileId');
		$this->setReferer();
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeSave()
	{
		$this->defaultsForAdd['sdaprofiles_profile_id'] = $this->input->get('profileId');
		$this->setRedirect($this->getReferer());
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onAfterSave()
	{
		$this->setRedirect($this->getReferer());
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeRemove()
	{
		$this->setReferer();
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onAfterRemove()
	{
		$this->setRedirect($this->getReferer());
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeEdit()
	{
		$this->setReferer();
	}

	/**
	 *
	 * @return void
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

	/**
	 * Adds an Equipment Item to a Profile
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function addFittingAjax()
	{
		$input = $this->input->post->getArray();

		if ($input['type'] && $input['detail'])
		{
			/** @var \Sda\Profiles\Site\Model\Fitting $fitting */
			$fitting                         = Container::getInstance('com_sdaprofiles')->factory->model('fitting');
			$fitting->sdaprofiles_profile_id = $input['profileId'];
			$fitting->type                   = $input['type'];
			$fitting->detail                 = $input['detail'];
			$fitting->length                 = $input['length'];
			$fitting->width                  = $input['width'];
			$fitting->save();

			$this->setRedirect('index.php?option=com_sdaprofiles&format=raw&view=Fitting&task=fittingAjax&id=' . $fitting->sdaprofiles_fitting_id);
		}
		else
		{
			$this->setRedirect('index.php?option=com_sdaprofiles&format=raw&view=Fitting&task=error');
		}

		$this->redirect;
	}

	/**
	 * Deletes an Equipment Item
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function deleteFittingAjax()
	{
		$input = $this->input->post->getArray();

		if ($input['id'])
		{
			/** @var \Sda\Profiles\Site\Model\Fitting $fitting */
			$fitting                         = Container::getInstance('com_sdaprofiles')->factory->model('fitting');
			$fitting->forceDelete($input['id']);
		}
		else
		{
			$this->setRedirect('index.php?option=com_sdaprofiles&format=raw&view=Fitting&task=error');
		}

		$this->redirect;
	}

}