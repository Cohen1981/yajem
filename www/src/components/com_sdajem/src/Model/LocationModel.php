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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * Location model for the Joomla Locations component.
 *
 * @since  1.0.0
 *
 * @property  int       id
 * @property  string    title
 * @property  string    alias
 * @property  string    description
 * @property  string    url
 * @property  int       catid
 * @property  string    language
 * @property  string    street
 * @property  string    postalCode
 * @property  string    city
 * @property  string    stateAddress
 * @property  string    country
 * @property  string    latlng
 * @property  string    image
  */

class LocationModel extends BaseDatabaseModel
{
	/**
	 * Linkt to com_contact
	 * @since 1.0.0
	 * @var int
	 */
	public int $organizerId;
	/**
	 * Link to com_users
	 * @since 1.0.0
	 * @var int
	 */
	public int $hostId;
	/**
	 * @var string item
	 * @since 1.0.0
	 */
	protected $_item = null;
	/**
	 * Gets a location
	 *
	 * @param   integer  $pk  Id for the location
	 *
	 * @return  mixed Object or null
	 *
	 * @since   1.0.0
	 */
	public function getItem($pk = null)
	{
		$app = Factory::getApplication();
		if ($pk === null)
			$pk  = ($pk) ? $pk : $app->input->getInt('id');

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
					->from($db->quoteName('#__sdajem_locations', 'a'))
					->where($db->quoteName('a.id') . '= :locationId');

				$query->bind(':locationId', $pk);
				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data))
				{
					throw new \Exception(Text::_('COM_SDAJEM_ERROR_LOCATION_NOT_FOUND'), 404);
				}

				$this->_item[$pk] = $data;
			}
			catch (\Exception $e)
			{
				$this->setError($e);
				$this->_item[$pk] = false;
			}
		}

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

		$this->setState('location.id', $app->input->getInt('id'));
		$this->setState('params', $app->getParams());
	}
}