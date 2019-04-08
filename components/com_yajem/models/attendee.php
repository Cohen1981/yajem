<?php
/**
 * @package     Yajem.Site
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

namespace Yajem\Site\Models;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

/**
 * @package     YAJEM
 *
 * @since       version
 */
class YajemModelAttendee extends ItemModel
{
	/**
	 * @param   null $id Attendee Primary id
	 *
	 * @return array|boolean|object
	 *
	 * @since 1.0
	 */
	public function &getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('id');
			}

			// Get a level row instance.
			$table = $this->getTable('Attendee', 'YajemTable');

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Convert the JTable to a clean JObject.
				$properties  = $table->getProperties(1);
				$this->_item = ArrayHelper::toObject($properties, 'JObject');
			}
		}

		return $this->_item;
	}

	/**
	 * @param   int|null    $id         Primary Key of Attendee Table
	 * @param   int         $eventId    EventId
	 * @param   int         $status     Status
	 *
	 * @return boolean
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	public function registration($id, $eventId, $status)
	{
		$user = Factory::getUser()->get('id');
		$table = $this->getTable('Attendee', 'YajemTable');

		if ($id)
		{
			$table->load($id);
			$table->set('status', $status);
		}
		else
		{
			$data['id'] = null;
			$data['userId'] = $user;
			$data['eventId'] = $eventId;
			$data['status'] = $status;
			$table->bind($data);

			if (!$table->check())
			{
				throw new Exception("Missing Data");
			}
		}

		$return = $table->store(true);

		$this->setState('id', $table->id);
		$this->__state_set = true;

		return $return;
	}
}