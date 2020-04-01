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
use Sda\Referer\Helper as RefererHelper;

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
		$referer = $this->input->server->getString('HTTP_REFERER');

		if ($referer != null)
		{
			RefererHelper::setReferer($referer);
		}
	}

	/**
	 * @return void
	 *
	 * @since 0.1.5
	 */
	public function onBeforeAdd()
	{
		try
		{
			$locationState = Factory::getApplication()->getUserState('locationState');

			$eventState = Factory::getApplication()->getUserState('eventState');

			if ($locationState)
			{
				$this->defaultsForAdd = $locationState;

				Factory::getApplication()->setUserState('locationState', null);
			}
		}
		catch (\Exception $e)
		{
		}

		if (! $eventState)
		{
			$referer = $this->input->server->getString('HTTP_REFERER');

			if ($referer != null)
			{
				RefererHelper::setReferer($referer);
			}
		}
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onAfterSave() : void
	{
		$this->setRedirect(RefererHelper::getReferer());
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onAfterCancel()
	{
		$this->setRedirect(RefererHelper::getReferer());
	}

	/**
	 * Redirect to add a category
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function addNewCategory()
	{
		$input = $this->input->getArray();

		if ($input['sdajem_location_id'] == '')
		{
			$referer = 'index.php?option=com_sdajem&view=Locations&task=add';
		}
		else
		{
			$referer = 'index.php?option=com_sdajem&view=Locations&task=edit&id='.$input['sdajem_location_id'];
		}

		try
		{
			Factory::getApplication()->setUserState('locationState', $input);
		}
		catch (\Exception $e)
		{
		}

		RefererHelper::setReferer($referer);
		$this->setRedirect('index.php?option=com_sdajem&view=Categories&task=add&type=0');
		$this->redirect();
	}
}