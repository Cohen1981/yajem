<?php
/**
 * @package     Yajem\Administrator\Helpers
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomla\Component\Yajem\Administrator\Helpers;

use Joomla\CMS\Language\Text;
use Joomla\Component\Yajem\Administrator\Classes\YajemEvent;
use Yajem\User\YajemUserProfile;
use Yajem\User\YajemUserProfiles;
use Yajem\Helpers\YajemParams;

require_once JPATH_SITE . '/administrator/components/com_yajem/helpers/YajemEventParams.php';
require_once JPATH_SITE . '/components/com_yajem/models/attendees.php';

/**
 * @package     Yajem\Administrator\Helpers
 *
 * @since       1.2.0
 */
class EventHtmlHelper
{
	/**
	 * @var \Joomla\Component\Yajem\Administrator\Helpers\YajemEventParams|null
	 * @since 1.2.0
	 */
	public $eventParams  = null;

	/**
	 * @var \stdClass|null
	 * @since 1.2.0
	 */
	public $links = null;

	/**
	 * @var \stdClass|null
	 * @since 1.2.0
	 */
	public $symbols = null;

	/**
	 * @var YajemParams|null
	 * @since 1.2.0
	 */
	public $yajemParams = null;

	/**
	 * @var int|null
	 * @since 1.2.1
	 */
	public $attendeeNumber = null;

	/**
	 * @var YajemUserProfiles
	 * @since 1.2.1
	 */
	public $userProfiles;

	/**
	 * @var integer
	 * @since 1.2.1
	 */
	public $eventId;

	/**
	 * YajemHtmlHelper constructor.
	 *
	 * @param   YajemEvent $event Event Object
	 *
	 * @since 1.2.0
	 */
	public function __construct($event)
	{
		$this->eventId      = $event->id;
		$this->yajemParams  = new YajemParams;
		$this->eventParams  = new YajemEventParams($event);
		$this->symbols      = new \stdClass;
		$this->links        = new \stdClass;
		$attendeesModel     = new \YajemModelAttendees;
		$this->userProfiles = new YajemUserProfiles;

		$this->attendeeNumber = $attendeesModel->getAttendeeNumber($event->id);

		self::getSymbols();
		self::getLinks();
	}

	/**
	 * @return void
	 *
	 * @since version
	 */
	private function getSymbols()
	{
			$this->symbols->open        = '<i class="fas fa-question-circle" aria-hidden="true" title="' .
											Text::_("COM_YAJEM_EVENT_STATUS_OPEN") . '"></i>';
			$this->symbols->confirmed   = '<i class="far fa-thumbs-up" aria-hidden="true" title="' .
											Text::_("COM_YAJEM_EVENT_STATUS_CONFIRMED") . '"></i>';
			$this->symbols->canceled    = '<i class="far fa-thumbs-down" aria-hidden="true" title="' .
											Text::_("COM_YAJEM_EVENT_STATUS_CANCELED") . '"></i>';
	}

	/**
	 *
	 * @return void
	 *
	 * @since version
	 */
	private function getLinks()
	{
		$waitingList = (!$this->attendeeNumber < $this->eventParams->registrationLimit && (bool) $this->eventParams->useWaitingList);

		if ($this->yajemParams->useAjaxCalls)
		{
			// Links for Event Status
			$this->links->confirm       = '<label id="yajem_confirm" class="green" onclick="switchEventStatus(\'confirm\')">' .
				$this->symbols->confirmed . '</label>';
			$this->links->cancel        = '<label id="yajem_canc" class="crimson" onclick="switchEventStatus(\'cancel\')">' .
				$this->symbols->canceled . '</label>';

			// Links for Attendings
			if ($waitingList)
			{
				$this->links->regButton = "<label id=\"yajem_reg\" class=\"yajem_css_switch yajem_rounded\" onclick=\"changeAttending('regw')\">" .
					Text::_('COM_YAJEM_REGW') . '</label>';
			}
			else
			{
				$this->links->regButton = "<label id=\"yajem_reg\" class=\"yajem_css_switch yajem_rounded\" onclick=\"changeAttending('reg')\">" .
					Text::_('COM_YAJEM_REG') . '</label>';
			}

			$this->links->unregButton = "<label id=\"yajem_unreg\" class=\"yajem_css_switch yajem_rounded\" onclick=\"changeAttending('unreg')\">" .
				Text::_('COM_YAJEM_UNREG') . '</label>';
		}
		else
		{
			// Links for Event Status
			$this->links->confirm       = '<label id="yajem_confirm" class="green" for="eConfirm">' .
				$this->symbols->confirmed . '</label>';
			$this->links->cancel        = '<label id="yajem_canc" class="crimson" for="eCancel">' .
				$this->symbols->canceled . '</label>';

			// Links for Attendings
			if ($waitingList)
			{
				$this->links->regButton = '<label id="yajem_reg" class="yajem_css_switch yajem_rounded" for="regw">' .
					Text::_('COM_YAJEM_REGW') . '</label>';
			}
			else
			{
				$this->links->regButton = '<label id="yajem_reg" class="yajem_css_switch yajem_rounded" for="reg">' .
					Text::_('COM_YAJEM_REG') . '</label>';
			}

			$this->links->unregButton = '<label id="yajem_unreg" class="yajem_css_switch yajem_rounded" for="unreg">' .
				Text::_('COM_YAJEM_UNREG') . '</label>';
		}
	}

	/**
	 * @param   int $userId         User Id
	 * @param   int $status         Status
	 * @param   int $attendingId    id of attendees table row
	 *
	 * @return string           Html
	 *
	 * @since 1.2.1
	 */
	public function getAttendingHtml($userId, $status, $attendingId):string
	{
		/**
		 * @var YajemUserProfile $profile
		 */
		$profile = $this->userProfiles->getProfile($userId);

		switch ($status)
		{
			case 0:
				$userStatus = '<div id="status_' . $profile->id . '" class="yajem_ustatus yajem_status_open">
					<i class="fas fa-question-circle" aria-hidden="true">
                    </i> ' . Text::_("COM_YAJEM_NOT_DECIDED") . '</div>';
				break;
			case 1:
				$userStatus = '<div id="status_' . $profile->id . '" class="yajem_ustatus yajem_status_attending">
					<i class="far fa-thumbs-up" aria-hidden="true">
                    </i> ' . Text::_("COM_YAJEM_ATTENDING") . '</div>';
				break;
			case 2:
				$userStatus = '<div id="status_' . $profile->id . '" class="yajem_ustatus yajem_status_declined">
					<i class="far fa-thumbs-down" aria-hidden="true">
					</i> ' . Text::_("COM_YAJEM_NOT_ATTENDING") . '</div>';
				break;
			case 3:
				$userStatus = '<div id="status_' . $profile->id . '" class="yajem_ustatus yajem_status_waiting">
					<i class="far fa-clock" aria-hidden="true">
                    </i> ' . Text::_("COM_YAJEM_ON_WAITINGLIST") . '</div>';
				break;
		}

		$html = "<div id=\"attendee_" . $profile->id . "\" class=\"yajem_attendee\">";

		if ($this->eventParams->useUserProfile)
		{
			$html = $html . "<div class=\"yajem_avatar_container\"><img class=\"yajem_avatar yajem_img_round\" src=\"" .
				$profile->avatar . "\"/></div>";
		}

		$html = $html . "<div class=\"yajem_att_status\"><div class=\"yajem_uname\">";
		$html = $html . $profile->name . "</div>" . $userStatus . "</div></div>";

		return $html;
	}

	/**
	 * @param   integer $userId User id
	 *
	 * @since 1.2.1
	 * @return string HTML
	 */
	public function getRegLinksAttendee($userId)
	{
		$user = $this->userProfiles->getProfile($userId);
		$status = $user->getEventAttendingStatus($this->eventId);

		switch ($status)
		{
			case 0:
				$html = $this->links->regButton;
				$html = $html . $this->links->unregButton;
				break;
			case 1:
				$html = $this->links->unregButton;
				break;
			case 2:
				$html = $this->links->regButton;
				break;
			case 3:
				$html = $this->links->unregButton;
				break;
		}

		/** @var string $html */
		return $html;
	}
}