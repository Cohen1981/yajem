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

use Joomla\CMS\MVC\View\HtmlView;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemViewEvent extends HtmlView
{
	/**
	 * @var state
	 * @since version
	 */
	protected $state;

	/**
	 * @var event
	 * @since version
	 */
	protected $event;

	/**
	 * @param   null $tpl Template to load
	 *
	 * @return mixed|void
	 *
	 * @since version
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');

		$this->event = $this->get('Data', 'Event');

		if ($this->event->locationId)
		{
			$this->location = $this->getModel('Locations')->getLocation($this->event->locationId);
		}

		$this->attendees = $this->getModel('Attendees')->getAttendees($this->event->id);
		$this->attendeeNumber = $this->getModel('Attendees')->getAttendeeNumber($this->event->id);

		if ($this->attendees)
		{
			foreach ($this->attendees as $i => $item)
			{
				$attArray[$item->userId] = $item;
			}

			$this->attendees = $attArray;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		parent::display($tpl);
	}
}