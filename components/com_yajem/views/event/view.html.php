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

		$this->event = $this->get('Data', 'Event');

		JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'models');
		$modelAttachments = JModelLegacy::getInstance('attachments', 'YajemModel');
		$this->event->attachments = $modelAttachments->getAttachments( (int) $this->event->id, 'event' );

		if ($this->event->locationId)
		{
			$this->location = $this->getModel('Locations')->getLocation($this->event->locationId);
			$this->location->attachments = $modelAttachments->getAttachments( (int) $this->location->id, 'location' );
		}

		if ($this->event->organizerId) {
			$this->event->organizer = YajemHelperAdmin::getUser($this->event->organizerId);
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

		$this->comments = $this->getModel('Comments')->getComments($this->event->id);
		$this->commentCount = $this->getModel('Comments')->getCommentCount($this->event->id);

		$this->userProfiles = YajemHelperAdmin::getUserList();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		YajemHelperAdmin::setDocument();
		parent::display($tpl);
	}
}