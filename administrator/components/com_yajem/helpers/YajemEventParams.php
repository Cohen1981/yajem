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
use Yajem\Administrator\Models\YajemModelEvent;

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
	 * @var string|null
	 * @since version
	 */
	public $googleApiKey = null;

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
	 * YajemEventParams constructor.
	 *
	 * @param   null|YajemModelEvent $event   Event Object
	 * @since 1.2.0
	 */
	public function __construct($event = null)
	{
		if ($event)
		{
			$user         = Factory::getUser();
			$this->guest  = (bool) $user->guest;
			$this->userId = $user->get('id');

			$useOrgG  = (bool) ComponentHelper::getParams('com_yajem')->get('use_organizer');
			$useHostG = (bool) ComponentHelper::getParams('com_yajem')->get('use_host');

			$this->useComments  = (bool) ComponentHelper::getParams('com_yajem')->get('use_comments');
			$this->googleApiKey = (string) ComponentHelper::getParams('com_yajem')->get('global_googleapi');

			$this->canCreate = $user->authorise('core.create', 'com_yajem');
			$this->canEdit   = $user->authorise('core.edit.state', 'com_yajem');

			$this->useReg = (bool) $event->useRegistration;
			$this->allDay = (bool) $event->allDayEvent;

			if ($useOrgG)
			{
				$this->useOrg = (bool) $event->useOrganizer;
			}

			if ($useHostG)
			{
				$this->useHost = (bool) $event->useHost;
			}
		}
	}

}