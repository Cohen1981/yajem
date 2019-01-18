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

JHtml::_('stylesheet', JUri::root() . 'media/com_yajem/css/style.css');

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\Utilities\ArrayHelper;
use Joomla\Event\Dispatcher;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemModelEvent extends ItemModel
{
	/**
	 * @param   null $ordering  Null
	 * @param   null $direction Null
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app  = JFactory::getApplication('com_yajem');

		$id = JFactory::getApplication()->input->get('id');

		$this->setState('item.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('item.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	 * @param   null $id Id of the Event to load
	 *
	 * @return  boolean|object
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function &getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('item.id');
			}

			// Get a level row instance.
			$table = $this->getTable('Event', 'YajemTable');

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Convert the JTable to a clean JObject.
				$properties  = $table->getProperties(1);
				$this->_item = ArrayHelper::toObject($properties, 'JObject');
			}
		}

		if (isset($this->_item->createdBy))
		{
			$this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
		}

		if (isset($this->_item->modifiedBy))
		{
			$this->_item->modified_by_name = JFactory::getUser($this->_item->modified_by)->name;
		}

		if (isset($this->_item->organizerId))
		{
			$db = JFactory::getDbo();
			$conQuery = $db->getQuery(true);
			$conQuery->select('name, user_id')
				->from('#__contact_details')
				->where('id = ' . $this->_item->organizerId);
			$db->setQuery($conQuery);
			$this->_item->organizer = $db->loadObject();
			$this->_item->organizerLink = "<a href='index.php?option=com_contact&view=contact&id=" .
				$this->_item->organizerId .
				"'>" . $this->_item->organizer->name . "</a>";
		}

		if (isset($this->_item->hostId))
		{
			$db = JFactory::getDbo();
			$conQuery = $db->getQuery(true);
			$conQuery->select('name')
				->from('#__contact_details')
				->where('id = ' . $this->_item->hostId);
			$db->setQuery($conQuery);
			$host = $db->loadResult();
			$this->_item->hostLink = "<a href='index.php?option=com_contact&view=contact&id=" .
				$this->_item->hostId .
				"'>" . $host . "</a>";
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('title')
			->from('#__categories')
			->where('id = ' . $this->_item->catid);

		$db->setQuery($query);

		$this->_item->cat_title = $db->loadResult();

		if (empty($this->_item->cat_title))
		{
			$this->_item->cat_title = $this->_item->catid;
		}

		return $this->_item;
	}

	/**
	 * @param   int $eventId    The id of the event to update
	 * @param   int $status     The new Status
	 *
	 * @return boolean true on success
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function changeStatus($eventId, $status)
	{
		$table = $this->getTable('Event', 'YajemTable');

		if ($eventId)
		{
			$table->load($eventId);
			$table->set('eventStatus', $status);
		}
		else
		{
			throw new Exception("No eventId");
		}

		return $table->store(true);
	}
}
