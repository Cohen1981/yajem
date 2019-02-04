<?php
/**
 * @package    yajem
 *
 * @author     abahl <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\Table\Table;
use Joomla\CMS\Factory;
use Joomla\Component\Yajem\Administrator\Helpers\tableHelper;
use Joomla\Filesystem\Folder;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

/**
 * Yajem table.
 *
 * @since       1.0
 */
class YajemTableEvent extends Table
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

	/**
	 * YajemTableEvent constructor.
	 *
	 * @param   JDatabaseDriver $db DB Driver
	 *
	 * @since   1.0
	 */
	public function __construct($db)
	{
		parent::__construct('#__yajem_events', 'id', $db);
	}

	/**
	 * Overloaded store method for events table
	 *
	 * @param   boolean $updateNulls   default null
	 *
	 * @return   boolean               true on success, else false
	 *
	 * @since   version                1.0
	 */
	public function store($updateNulls = false)
	{
		$date = Factory::getDate()->toSql();
		$user = Factory::getUser()->get('id');

		$this->modified = $date;
		$this->modifiedBy = $user;

		if (empty($this->id))
		{
			// New record
			$this->created = $date;
			$this->createdBy = $user;
		}

		// Generating the alias
		if (empty($this->alias))
		{
			$this->alias = $this->title;
			$this->alias = JApplicationHelper::stringURLSafe($this->alias, $this->language);

			if (trim(str_replace('-', '', $this->alias)) == '')
			{
				$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
			}
		}

		if (!$this->eventStatus && (bool) JComponentHelper::getParams('com_yajem')->get('use_organizer'))
		{
			$this->eventStatus = 0;
		}

		if ((bool) $this->allDayEvent)
		{
			$this->startDateTime = $this->startDate;
			$this->endDateTime = $this->endDate;
		}
		else
		{
			$this->startDate = $this->startDateTime;
			$this->endDate = $this->endDateTime;
		}

		return parent::store($updateNulls);
	}

	/**
	 * Overloaded bind function
	 *
	 * @param   array|object $src       Src
	 * @param   array        $ignore    Params
	 *
	 * @return boolean
	 *
	 * @since 1.0
	 */
	public function bind($src, $ignore = array())
	{
		if (isset($src['params']) && is_array($src['params']))
		{
			// Convert the params array to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($src['params']);
			$src['image'] = (string) $parameter;
		}

		return parent::bind($src, $ignore);
	}

	/**
	 * Overloaded delete function for enforcing data integrity
	 * Should also work when called from Frontend
	 *
	 * @param   null $pk Primär Key
	 *
	 * @return boolean
	 *
	 * @since 1.0
	 */
	public function delete($pk = null)
	{
		$return = parent::delete($pk);

		if ($return)
		{
			$tableHelper = new tableHelper;

			$tableHelper->deleteForeignTable('#__yajem_attendees', 'Attendee', 'eventId', $pk);

			$tableHelper->deleteForeignTable('#__yajem_comments', 'Comment', 'eventId', $pk);

			$tableHelper->deleteForeignTable('#__yajem_attachments', 'Attachment', 'eventId', $pk);
		}

		if (JFolder::exists(YAJEM_UPLOADS . DIRECTORY_SEPARATOR . 'event' . $pk))
		{
			Folder::delete(YAJEM_UPLOADS . DIRECTORY_SEPARATOR . 'event' . $pk);
		}

		return $return;
	}
}