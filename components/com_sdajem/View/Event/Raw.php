<?php
/**
 * @package     Sda\Jem\Site\View\Fitting
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Site\View\Event;

use FOF30\View\DataView\Raw as BaseRaw;
use Sda\Jem\Site\Model\Event;

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
			case "getRegisterHtml":
				/** @var Event $event */
				$event = $this->getModel();
				$event->load($input['id]']);
				$this->setLayout('register');
				break;
			case "changeEventStatus":
				/** @var Event $event */
				$event = $this->getModel();
				$event->load($input['id]']);
				$this->setLayout('eventStatus');
				break;
			case "error":
				$this->setLayout('error');
				break;
		}
	}
}