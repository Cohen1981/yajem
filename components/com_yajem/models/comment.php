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

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

/**
 * @package     YAJEM
 *
 * @since       1.0
 */
class YajemModelComment extends ItemModel
{
	/**
	 * @param   null $id Attendee Primary id
	 *
	 * @return array|boolean|object
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
				$id = $this->getState('id');
			}

			// Get a level row instance.
			$table = $this->getTable('Comment', 'YajemTable');

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
	 * @param   int         $userId     userId
	 * @param   int         $eventId    EventId
	 * @param   text        $comment    Comment
	 *
	 * @return boolean
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	public function comment($userId, $eventId, $comment)
	{
		$timestamp = Factory::getDate()->toSql();
		$table = $this->getTable('Comment', 'YajemTable');

		$data['id'] = null;
		$data['userId'] = $userId;
		$data['eventId'] = $eventId;
		$data['comment'] = $comment;
		$data['timestamp'] = $timestamp;
		$table->bind($data);

		if (!$table->check())
		{
			throw new Exception("Missing Data");
		}

		return $table->store(true);
	}
}