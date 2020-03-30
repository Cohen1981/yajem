<?php

namespace Sda\Profiles\Site\Controller;

use FOF30\Controller\DataController;

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
}