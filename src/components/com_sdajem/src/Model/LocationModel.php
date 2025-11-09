<?php
/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Sda\Component\Sdajem\Site\Model\Item\LocationItem;

/**
 * Location model for the Joomla Locations component.
 *
 * @since  1.0.0
 */
class LocationModel extends BaseDatabaseModel
{

	protected LocationItem|null $_item = null;

	/**
	 * Gets a location
	 *
	 * @param   integer  $pk  Id for the location
	 *
	 * @return  mixed Object or null
	 *
	 * @throws Exception
	 * @since   1.0.0
	 */
	public function getItem($pk = null): LocationItem
	{
		$app = Factory::getApplication();

		$pk  = !$pk ? $app->input->getInt('id') : $pk;

		if ($this->_item === null && $pk !== null)
		{
			try
			{
				$db    = $this->getDatabase();
				$query = $db->getQuery(true);

				$query->select('*')
					->from($db->quoteName('#__sdajem_locations', 'a'))
					->where($db->quoteName('a.id') . '= :locationId');

				$query->bind(':locationId', $pk);
				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data))
				{
					throw new Exception(Text::_('COM_SDAJEM_ERROR_LOCATION_NOT_FOUND'), 404);
				}

				$this->_item = LocationItem::createFromObject($data);
			}
			catch (Exception $e)
			{
				$app->enqueueMessage($e->getMessage(), 'error');
				$this->_item->id = null;
			}
		}
		else {
			$this->_item = new LocationItem();
		}

		return $this->_item;
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

		$this->setState('location.id', $app->input->getInt('id'));
		$this->setState('params', $app->getParams());
	}
}