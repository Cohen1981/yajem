<?php
/**
 * @package     Sda\Jem\Site\View\Fitting
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Site\View\Attendee;

use FOF30\View\DataView\Raw as BaseRaw;
use Sda\Jem\Site\Model\Attendee;

/**
 * @package     Sda\Profiles\Site\View\Attendee
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
			case "error":
				$this->setLayout('error');
				break;
		}
	}
}