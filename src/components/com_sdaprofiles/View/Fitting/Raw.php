<?php
/**
 * @package     Sda\Profiles\Site\View\Fitting
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Profiles\Site\View\Fitting;

use FOF30\View\DataView\Raw as BaseRaw;

/**
 * @package     Sda\Profiles\Site\View\Fitting
 *
 * @since       0.0.1
 */
class Raw extends BaseRaw
{
	/**
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeMain()
	{
		$input = $this->input->request->getArray();

		switch ($input['task'])
		{
			case "fittingAjax":
				$this->setLayout('fitting');
				break;
			case "error":
				$this->setLayout('error');
				break;
			case "editAjax":
				$fitting = $this->getModel();
				$fitting->load($input['id']);
				$this->setLayout('form');
				break;
			case "add":
				$this->setLayout('form');
				break;
		}
	}
}