<?php

namespace Sda\Profiles\Site\Controller;

use FOF30\Controller\DataController;
use Joomla\CMS\Factory;
use Sda\Referer\Helper as RefererHelper;

/**
 * Class FittingImage
 * @package Sda\Profiles\Site\Controller
 * @since 1.1.0
 */
class FittingImage extends DataController
{
	/**
	 * @param int id if null will check post input for 'id'
	 * @since 1.1.0
	 */
	public function deleteFittingImage(int $id = null) {
		if (!$id) {
			$input = $this->input->request->post->getArray();
			$id = $input;
		}

		/** @var \Sda\Profiles\Site\Model\FittingImage $fImage */
		$fImage = $this->getModel('FittingImage');
		$fImage->load($id);

		if ($fImage->fittings) {
			if ($fImage->fittings->count() > 0) {
				$this->setRedirect('index.php?option=com_sdaprofiles&format=raw&view=FittingImage&task=checked_error');
			} else {
				$fImage->delete($id);
				$this->setRedirect('index.php?option=com_sdaprofiles&format=raw&view=FittingImage&task=checked_ok');
			}
		} else {
			$fImage->delete($id);
			$this->setRedirect('index.php?option=com_sdaprofiles&format=raw&view=FittingImage&task=checked_ok');
		}

		$this->redirect();
	}

	/**
	 * @since 1.1.0
	 */
	public function addNewType()
	{
		$input = $this->input->getArray();

		if ($input['sdaprofiles_fitting_image_id'] == '')
		{
			$referer = 'index.php?option=com_sdaprofiles&view=FittingImages&task=add';
		}
		else
		{
			$referer = 'index.php?option=com_sdaprofiles&view=FittingImages&task=edit&id='.$input['sdaprofiles_fitting_type_id'];
		}

		try
		{
			Factory::getApplication()->setUserState('eventState', $input);
		}
		catch (\Exception $e)
		{
		}

		RefererHelper::setReferer($referer);

		$this->setRedirect('index.php?option=com_sdaprofiles&view=FittingType&task=add');
		$this->redirect();
	}
}