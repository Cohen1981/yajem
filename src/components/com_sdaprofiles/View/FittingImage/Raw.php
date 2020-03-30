<?php
/**
 * @package     Sda\Jem\Site\View\Fitting
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Profiles\Site\View\FittingImage;

use FOF30\View\DataView\Raw as BaseRaw;
use Sda\Jem\Site\Model\Event;
use Joomla\CMS\Factory;

/**
 * @package     Sda\Profiles\Site\View\Event
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
			case "checked_ok":
				$this->setLayout('clear');
				break;
			case "checked_error":
				$this->setLayout('error');
				break;
		}
	}
}