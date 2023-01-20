<?php
/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage  com_sdajem
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * @package     Sda\Component\Sdajem\Site\Model
 *
 * @since       __BUMP_VERSION__
 *
 * Fields:
 *
 * @property   int			$id
 * @property   string		$title
 * @property   string		$alias
 */
class EventModel extends BaseDatabaseModel
{
	/**
	 * @var string item
	 */
	protected $_item = null;

	/**
	 * Gets an event
	 *
	 * @param   integer  $pk  Id for the foo
	 *
	 * @return  mixed Object or null
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function getItem($pk = null)
	{
		$app = Factory::getApplication();
		$pk = $app->input->getInt('id');

		if ($this->_item === null) {
			$this->_item = [];
		}

		if (!isset($this->_item[$pk])) {
			try {
				$db = $this->getDatabase();
				$query = $db->getQuery(true);

				$query->select('*')
					->from($db->quoteName('#__sda_events', 'a'))
					->where('a.id = ' . (int) $pk);

				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data)) {
					throw new \Exception(Text::_('COM_EVENTS_ERROR_EVENT_NOT_FOUND'), 404);
				}

				$this->_item[$pk] = $data;
			} catch (\Exception $e) {
				$this->setError($e);
				$this->_item[$pk] = false;
			}
		}

		return $this->_item[$pk];
	}

	protected function populateState()
	{
		$app = Factory::getApplication();
		$this->setState('event.id', $app->input->getInt('id'));
		$this->setState('params', $app->getParams());
	}
}