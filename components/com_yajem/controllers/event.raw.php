<?php
/**
 * @package     Yajem.Site
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

/**
 * @package     Yajem
 *
 * @since       1.1
 */
class YajemControllerEvent extends JControllerLegacy
{
	/**
	 *
	 * @return JControllerLegacy|void
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function view()
	{
		$view = $this->getView('Event', 'raw');
		$view->setModel($this->getModel('Event'));
		$view->setModel($this->getModel('Attendees'));
		$view->setModel($this->getModel('Locations'));

		$view->display();
	}

	public function getIcs() {
		if ($view = $this->getView('Event', 'raw'))
		{
			$view->setModel($this->getModel('Event'));
			$view->setModel($this->getModel('Locations'));

			$view->document = JFactory::getDocument();

			$view->display();
		}
	}
}
