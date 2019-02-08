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
use Joomla\Component\Yajem\Administrator\Helpers\EventHtmlHelper;
use Joomla\Component\Yajem\Administrator\Classes\YajemEvent;
use Joomla\Component\Yajem\Administrator\Classes\YajemLocation;
use Joomla\Component\Yajem\Administrator\Classes\YajemUserProfiles;

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
	 * @var YajemEvent $event
	 * @since 1.0
	 */
	protected $event;

	/**
	 * @var YajemLocation $location
	 * @since version
	 */
	protected $location;

	/**
	 * @var EventHtmlHelper
	 * @since 1.2.1
	 */
	protected $yajemHtmlHelper;

	/**
	 * @var YajemUserProfiles
	 * @since 1.2.1
	 */
	protected $userProfiles;

	protected $eventParams;

	protected $eventSymbols;

	protected $eventLinks;

	protected $organizer;

	/**
	 * @var
	 * @since version
	 */
	protected $attendees;

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
		$this->event = $this->get('Data', 'Event');
		$this->state = $this->get('State');

		require_once JPATH_ADMINISTRATOR . '/components/com_yajem/helpers/EventHtmlHelper.php';
		$this->yajemHtmlHelper = new EventHtmlHelper($this->event);

		// For shorter access
		$this->eventParams  = $this->yajemHtmlHelper->eventParams;
		$this->eventSymbols = $this->yajemHtmlHelper->symbols;
		$this->eventLinks   = $this->yajemHtmlHelper->links;

		// Get userProfiles
		require_once JPATH_SITE . "/administrator/components/com_yajem/Classes/YajemUserProfiles.php";
		$yajemUserProfiles = new YajemUserProfiles;
		$this->userProfiles = $yajemUserProfiles->getProfiles();

		// Getting event attachments
		JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'models');
		$modelAttachments = JModelLegacy::getInstance('attachments', 'YajemModel');
		$this->event->attachments = $modelAttachments->getAttachments((int) $this->event->id, 'event');

		// If we have a location for the event get it
		if ($this->event->locationId)
		{
			$this->location = $this->getModel('Locations')->getLocation($this->event->locationId);
			$this->location->attachments = $modelAttachments->getAttachments((int) $this->location->id, 'location');
		}

		// If we have an organizer get the profile as shortcut
		if ($this->event->organizerId)
		{
			$this->organizer = $yajemUserProfiles->getProfile($this->event->organizerId);
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

		// Get the comments
		$this->comments = $this->getModel('Comments')->getComments($this->event->id);
		$this->commentCount = $this->getModel('Comments')->getCommentCount($this->event->id);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		parent::display($tpl);
		YajemHelperAdmin::setDocument();
	}
}