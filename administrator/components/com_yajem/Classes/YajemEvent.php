<?php
/**
 * @package     Joomla\Component\Yajem\Administrator\Classes
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomla\Component\Yajem\Administrator\Classes;

use DateTime;
/**
 * @package     Joomla\Component\Yajem\Administrator\Classes
 *
 * @since       version
 */
class YajemEvent
{
	/**
	 * @var		int|null
	 * @since	version
	 */
	public $id = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $catid = null;

	/**
	 * @var		int|bool|null
	 * @since	version
	 */
	public $published = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $ordering = null;

	/**
	 * @var		DateTime|null
	 * @since	version
	 */
	public $created = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $createdBy = null;

	/**
	 * @var		DateTime|null
	 * @since	version
	 */
	public $modified = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $modifiedBy = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $title = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $alias = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $description = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $url = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $image = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $locationId = null;

	/**
	 * @var		int|bool|null
	 * @since	version
	 */
	public $useHost = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $hostId = null;

	/**
	 * @var		int|bool|null
	 * @since	version
	 */
	public $useOrganizer = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $organizerId = null;

	/**
	 * @var		DateTime|null
	 * @since	version
	 */
	public $startDateTime = null;

	/**
	 * @var		DateTime|null
	 * @since	version
	 */
	public $endDateTime = null;

	/**
	 * @var		DateTime|null
	 * @since	version
	 */
	public $startDate = null;

	/**
	 * @var		DateTime|null
	 * @since	version
	 */
	public $endDate = null;

	/**
	 * @var		int|bool|null
	 * @since	version
	 */
	public $allDayEvent = null;

	/**
	 * @var		int|bool|null
	 * @since	version
	 */
	public $useRegistration = null;

	/**
	 * @var		DateTime|null
	 * @since	version
	 */
	public $registerUntil = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $registrationLimit = null;

	/**
	 * @var		int|bool|null
	 * @since	version
	 */
	public $useWaitingList = null;

	/**
	 * @var		int|bool|null
	 * @since	version
	 */
	public $useInvitation = null;

	/**
	 * @var		int|bool|null
	 * @since	version
	 */
	public $eventStatus = null;

	/**
	 * @var		int|bool|null
	 * @since	version
	 */
	public $useRegisterUntil = null;
}