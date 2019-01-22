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
use com_yajem\administrator\helpers\tableHelper;

defined('_JEXEC') or die;
require_once (JPATH_COMPONENT_ADMINISTRATOR . '/helpers/tableHelper.php');

/**
 * Yajem table.
 *
 * @since       1.0
 */
class YajemTableEvent extends Table
{
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
			$this->startDateTime =	$this->startDate;
			$this->endDateTime = $this->endDate;
		} else {
			$this->startDate =	$this->startDateTime;
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
	 * @param null $pk
	 *
	 * @return bool
	 *
	 * @since 1.0
	 */
	public function delete($pk = null)
	{
		//$return = parent::delete($pk);
		$return = true;

		if ($return)
		{
			$tableHelper = new tableHelper();

			$tableHelper->deleteForeignTable('#__yajem_attendees', 'Attendee', 'eventId', $pk);

			$tableHelper->deleteForeignTable('#__yajem_comments', 'Comment', 'eventId', $pk);

			$tableHelper->deleteForeignTable('#__yajem_attachments', 'Attachment', 'eventId', $pk);
		}

		return $return;
	}
}