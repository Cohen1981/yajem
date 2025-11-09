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
use Sda\Component\Sdajem\Site\Model\Item\EventItem;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Event model for the Joomla Events component.
 *
 * @since  1.0.0
 */

class EventModel extends BaseDatabaseModel
{
	protected EventItem|null $_item = null;
	/**
	 * Gets an event
	 *
	 * @param   int  $pk  pk for the event
	 *
	 * @return  Object|null or null
	 *
	 * @since   1.0.0
	 */
	public function getItem(int $pk = null): EventItem
	{
		$app = Factory::getApplication();
		$pk  = ($pk) ? $pk : $app->input->getInt('id');

		if ($this->_item === null && $pk !== null)
		{
			try
			{
				$db    = $this->getDatabase();
				$query = $db->getQuery(true);

				$query->select('a.*')
					->from($db->quoteName('#__sdajem_events', 'a'))
					->where($db->quoteName('a.id') . ' = :eventId')
					->bind(':eventId', $pk);
					#->where('a.id = ' . (int) $pk);

				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data))
				{
					throw new Exception(Text::_('COM_SDAJEM_ERROR_EVENT_NOT_FOUND'), 404);
				}

				if($data->svg)
					$data->svg = (array) json_decode($data->svg);
				else
					$data->svg = array();
				$this->_item = EventItem::createFromObject($data);
			}
			catch (Exception $e)
			{
				$app->enqueueMessage($e->getMessage(),'error');
				$this->_item = new EventItem();
			}
		}

		return $this->_item;
	}

	/**
	 * Method to autopopulate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @throws Exception
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