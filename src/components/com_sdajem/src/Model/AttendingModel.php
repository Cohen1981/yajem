<?php 




/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Sda\Component\Sdajem\Administrator\Library\Enums\IntAttStatusEnum;
use function defined;

defined('_JEXEC') or die;

/**
 * Event model for the Joomla Events component.
 *
 * @since  1.0.0
 *
 */

class AttendingModel extends BaseDatabaseModel
{
	/**
	 * @var string item
	 * @since 1.0.0
	 */
	protected $_item = null;

	/**
	 * Gets a event
	 *
	 * @param   null  $pk  Id for the event
	 *
	 * @return  mixed Object or null
	 *
	 * @throws Exception
	 * @since   1.0.0
	 */
	public function getItem($pk = null)
	{
		$app = Factory::getApplication();
		$pk  = ($pk) ?: $app->input->getInt('id');

		if ($this->_item === null)
		{
			$this->_item = [];
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{
				$db    = $this->getDatabase();
				$query = $db->getQuery(true);

				$query->select('*')
					->from($db->quoteName('#__sdajem_attendings', 'a'))
					->where($db->quoteName('a.id') . ' = :attendingId');

				$query->select($db->quoteName('e.title', 'eventTitle'))
					->join(
						'LEFT',
						$db->quoteName('#__sdajem_events', 'e') . ' ON ' . $db->quoteName('e.id') . '=' . $db->quoteName('a.event_id')
					);

				//Join over User as attendee
				$query->select($db->quoteName('at.username', 'attendeeName'))
					->join(
						'LEFT',
						$db->quoteName('#__users', 'at') . ' ON ' . $db->quoteName('at.id') . ' = ' . $db->quoteName('a.users_user_id')
					);

				$query->bind(':attendingID', $pk);

				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data))
				{
					throw new Exception(Text::_('COM_SDAJEM_ERROR_ATTENDING_NOT_FOUND'), 404);
				}

				if (isset($data->fittings)) {
					$data->fittings = json_decode($data->fittings, true);
				}

				$this->_item[$pk] = $data;
			}
			catch (Exception $e)
			{
				$app->enqueueMessage($e->getMessage(),'error');
				$this->_item[$pk] = false;
			}
		}
		$this->_item[$pk]->status = IntAttStatusEnum::from($this->_item[$pk]->status);

		return $this->_item[$pk];
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @throws Exception
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		$this->setState('attending.id', $app->input->getInt('id'));
		$this->setState('params', $app->getParams());
	}
}