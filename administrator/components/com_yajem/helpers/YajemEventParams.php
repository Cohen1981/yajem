<?php
/**
 * @package     Yajem\Administrator\Helpers
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomla\Component\Yajem\Administrator\Helpers;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\Component\Yajem\Administrator\Classes\YajemEvent;
use Yajem\Helpers\YajemParams;

/**
 * @package     Yajem\Administrator\Helpers
 *
 * @since       1.2.0
 */
class YajemEventParams
{
	/**
	 * @var bool|null
	 * @since version
	 */
	public $isGuest = null;

	/**
	 * Current logged in User
	 * @var int|null
	 * @since version
	 */
	public $userId = null;

	/**
	 * @var bool|null
	 * @since version
	 */
	public $useComments = null;

	/**
	 * @var bool|null
	 * @since version
	 */
	public $canCreate = null;

	/**
	 * @var bool|null
	 * @since version
	 */
	public $canEdit = null;

	/**
	 * @var bool|null
	 * @since version
	 */
	public $useReg = null;

	/**
	 * @var bool|null
	 * @since version
	 */
	public $allDay = null;

	/**
	 * @var bool|null
	 * @since version
	 */
	public $useOrg = null;

	/**
	 * @var bool|null
	 * @since version
	 */
	public $useHost = null;

	/**
	 * @var bool|null
	 * @since 1.2.0
	 */
	public $useUserProfile;

	/**
	 * @var bool|null
	 * @since 1.2.0
	 */
	public $useAjaxCalls = null;

	/**
	 * @var int|null
	 * @since 1.2.1
	 */
	public $registrationLimit = null;

	/**
	 * @var bool|null
	 * @since 1.2.1
	 */
	public $useWaitingList = null;

	/**
	 * YajemEventParams constructor.
	 *
	 * @param   null|YajemEvent $event Event Object
	 *
	 * @since 1.2.0
	 */
	public function __construct($event = null)
	{
		if ($event)
		{
			$user         = Factory::getUser();
			$this->isGuest  = (bool) $user->guest;
			$this->userId = $user->get('id');

			$params = new YajemParams;

			$this->useComments  = $params->useComments;
			$this->useUserProfile = $params->useUserProfile;
			$this->useAjaxCalls = $params->useAjaxCalls;

			$this->canCreate = $user->authorise('core.create', 'com_yajem');
			$this->canEdit   = $user->authorise('core.edit.state', 'com_yajem');

			$this->useReg = (bool) $event->useRegistration;
			$this->allDay = (bool) $event->allDayEvent;

			if ($params->useHost)
			{
				$this->useHost = (bool) $event->useOrganizer;
			}

			if ($params->useOrg)
			{
				$this->useOrg = (bool) $event->useHost;
			}

			$this->registrationLimit = $event->registrationLimit;
			$this->useWaitingList = (bool) $event->useWaitingList;
		}
	}

}