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

/**
 * Location model for the Joomla Locations component.
 *
 * @since  __BUMP_VERSION__
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
  */

class LocationModel extends BaseDatabaseModel
{
	/**
	 * Linkt to com_contact
	 * @since version
	 * @var int
	 */
	public int $organizerId;
	/**
	 * Link to com_users
	 * @since version
	 * @var int
	 */
	public int $hostId;
	/**
	 * @var string item
	 * @since __BUMP_VERSION__
	 */
	protected $_item = null;
	/**
	 * Gets a location
	 *
	 * @param   integer  $pk  Id for the location
	 *
	 * @return  mixed Object or null
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function getItem($pk = null)
	{
		$app = Factory::getApplication();
		if ($pk === null)
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
					->from($db->quoteName('#__sdajem_locations', 'a'))
					->where('a.id = ' . (int) $pk);

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
	 * @since   __BUMP_VERSION__
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		$this->setState('location.id', $app->input->getInt('id'));
		$this->setState('params', $app->getParams());
	}
}