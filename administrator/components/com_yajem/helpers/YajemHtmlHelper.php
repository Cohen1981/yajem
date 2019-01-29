<?php
/**
 * @package     Yajem\Administrator\Helpers
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Yajem\Administrator\Helpers;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;

/**
 * @package     Yajem\Administrator\Helpers
 *
 * @since       1.2.0
 */
class YajemHtmlHelper
{
	/**
	 * @var \stdClass|null
	 * @since 1.2.0
	 */
	public $params  = null;
	/**
	 * @var \stdClass|null
	 * @since 1.2.0
	 */
	public $links   = null;
	/**
	 * @var \stdClass|null
	 * @since 1.2.0
	 */
	public $symbols = null;

	/**
	 * YajemHtmlHelper constructor.
	 *
	 * @param null $event
	 *
	 * @since 1.2.0
	 */
	public function __construct($event = null)
	{
		$this->params   = new \stdClass();
		$this->symbols  = new \stdClass();
		$this->links    = new \stdClass();

		if ($event) {
			self::getEventParams($event);
		}
	}

	/**
	 * @param $event
	 *
	 *
	 * @since 1.2.0
	 */
	public function getEventParams ($event) {

		$user                       = Factory::getUser();
		$this->params->guest        = (bool) $user->guest;
		$this->params->userId       = $user->get('id');

		$useOrgG                    = (bool) ComponentHelper::getParams('com_yajem')->get('use_organizer');
		$useHostG                   = (bool) ComponentHelper::getParams('com_yajem')->get('use_host');

		$this->params->useComments  = (bool) ComponentHelper::getParams('com_yajem')->get('use_comments');
		$this->params->googleApiKey = (string) ComponentHelper::getParams('com_yajem')->get('global_googleapi');

		$this->params->canCreate    = $user->authorise('core.create', 'com_yajem');
		$this->params->canEdit      = $user->authorise('core.edit.state', 'com_yajem');

		$this->params->useReg       = (bool) $event->useRegistration;
		$this->params->allDay       = (bool) $event->allDayEvent;

		if ($useOrgG)
		{
			$this->params->useOrg   = (bool) $event->useOrganizer;
		}
		if ($useHostG)
		{
			$this->params->useHost  = (bool) $event->useHost;
		}

		if ($this->params->useOrg)
		{
			$this->symbols->open        = '<i class="fas fa-question-circle" aria-hidden="true" title="' . Text::_("COM_YAJEM_EVENT_STATUS_OPEN") . '"></i>';
			$this->symbols->confirmed    = '<i class="far fa-thumbs-up" aria-hidden="true" title="' . Text::_("COM_YAJEM_EVENT_STATUS_CONFIRMED") . '"></i>';
			$this->symbols->canceled    = '<i class="far fa-thumbs-down" aria-hidden="true" title="' . Text::_("COM_YAJEM_EVENT_STATUS_CANCELED") . '"></i>';

			$this->links->confirm       = '<label id="yajem_confirm" class="green" for="eConfirm">' . $this->symbols->confirmed . '</label>';
			$this->links->cancel        = '<label id="yajem_canc" class="crimson" for="eCancel">' . $this->symbols->canceled . '</label>';
		}

	}
}