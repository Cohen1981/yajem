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
 * @since       version
 */
class YajemViewEvent extends JViewLegacy
{
	/**
	 * @var state
	 * @since 1.0
	 */
	protected $state;

	/**
	 * @var event
	 * @since 1.0
	 */
	protected $event;

	/**
	 * @param   null $tpl Template to load
	 *
	 * @return mixed|void
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');

		$ics = $this->getModel('Event')->makeIcs();

		$document = JFactory::getDocument();
		$document->setMimeEncoding('text/calendar; charset=utf-8');
		JFactory::getApplication()
			->setHeader(
				'Content-disposition',
				'attachment; filename="invite.ics"; creation-date="' . JFactory::getDate()->toRFC822() . '"',
				true
			)
			->setHeader('Content-Type','text/calendar; charset=utf-8', true)
			->setHeader('Content-Length',strlen($ics),true)
			->setHeader('Connection', 'close', true);

		echo $ics;
	}

}