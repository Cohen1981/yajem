<?php
/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Language\Text;
use Joomla\Component\Categories\Administrator\Model\CategoryModel;
use Sda\Component\Sdajem\Site\Enums\AttendingStatusEnum;

/**
 * Event model for the Joomla Events component.
 *
 * @since  1.0.0
 *
 * Fields
 * @property  int       id
 * @property  int       access
 * @property  string    alias
 * @property  Date      created
 * @property  int       created_by
 * @property  string    created_by_alias
 * @property  int       state
 * @property  int       ordering
 * @property  int       event_id
 * @property  int       users_user_id
 * @property  int       status
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
	 * @param   integer  $pk  Id for the event
	 *
	 * @return  mixed Object or null
	 *
	 * @since   1.0.0
	 */
	public function getItem($pk = null)
	{
		$app = Factory::getApplication();
		$pk  = $app->input->getInt('id');

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
					->where('a.id = ' . (int) $pk);

				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data))
				{
					throw new \Exception(Text::_('COM_SDAJEM_ERROR_ATTENDING_NOT_FOUND'), 404);
				}

				$this->_item[$pk] = $data;
			}
			catch (\Exception $e)
			{
				$this->setError($e);
				$this->_item[$pk] = false;
			}
		}
		$this->_item[$pk]['status'] = AttendingStatusEnum::from($this->_item[$pk]['status']);

		return $this->_item[$pk];
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		$this->setState('event.id', $app->input->getInt('id'));
		$this->setState('params', $app->getParams());
	}
}