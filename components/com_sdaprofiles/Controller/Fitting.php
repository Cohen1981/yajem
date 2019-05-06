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
use Sda\Profiles\Admin\Helper\RefererHelper;

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
		RefererHelper::setReferer($this->input->server->getString('HTTP_REFERER'));
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeSave()
	{
		$this->defaultsForAdd['sdaprofiles_profile_id'] = $this->input->get('profileId');
		$this->setRedirect(RefererHelper::getReferer());
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onAfterSave()
	{
		$this->setRedirect(RefererHelper::getReferer());
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeRemove()
	{
		RefererHelper::setReferer($this->input->server->getString('HTTP_REFERER'));
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onAfterRemove()
	{
		$this->setRedirect(RefererHelper::getReferer());
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onBeforeEdit()
	{
		RefererHelper::setReferer($this->input->server->getString('HTTP_REFERER'));
	}

	/**
	 *
	 * @return void
	 * @since 0.0.1
	 */
	public function onAfterCancel()
	{
		$this->setRedirect(RefererHelper::getReferer());
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

		//TODO save standard field
		if ($input['type'])
		{
			/** @var \Sda\Profiles\Site\Model\Fitting $fitting */
			$fitting = Container::getInstance('com_sdaprofiles')->factory->model('fitting');

			if ($input['profileId'] == '')
			{
				/** @var \Sda\Profiles\Admin\Model\User $user */
				$user = Container::getInstance('com_sdaprofiles')->factory->model('user');
				$user->load(Factory::getUser()->id);
				$fitting->sdaprofiles_profile_id = $user->profile->sdaprofiles_profile_id;
			}
			else
			{
				$fitting->sdaprofiles_profile_id = $input['profileId'];
			}

			$fitting->sdaprofiles_fitting_id        = ($input['sdaprofiles_fitting_id'] != '') ? $input['sdaprofiles_fitting_id'] : null;
			$fitting->type                          = $input['type'];
			$fitting->detail                        = $input['detail'];
			$fitting->length                        = $input['length'];
			$fitting->width                         = $input['width'];
			$fitting->sdaprofiles_fitting_image_id  = $input['image'];

			if ($input['standard'])
			{
				$fitting->standard = true;
			}
			else
			{
				$fitting->standard = false;
			}

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