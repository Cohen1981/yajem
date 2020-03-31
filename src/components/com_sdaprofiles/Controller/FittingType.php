<?php

namespace Sda\Profiles\Site\Controller;

use FOF30\Controller\DataController;
use Sda\Referer\Helper as RefererHelper;

/**
 * Class FittingType
 * @package Sda\Profiles\Site\Controller
 * @since 1.1.0
 */
class FittingType extends DataController
{
	/**
	 * @return void
	 *
	 * @since 1.1.0
	 */
	public function onAfterSave() : void
	{
		$this->setRedirect(RefererHelper::getReferer());
	}

	/**
	 * @return void
	 *
	 * @since 1.1.0
	 */
	public function onAfterCancel()
	{
		$this->setRedirect(RefererHelper::getReferer());
	}
}