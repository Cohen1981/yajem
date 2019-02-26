<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ItemModel;
use Yajem\User\YajemUserProfile;

/**
 * @package     ${NAMESPACE}
 *
 * @since       1.0.0
 */
class YajemModelProfile extends ItemModel
{
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

			$this->_item = new YajemUserProfile($id);
		}

		return $this->_item;
	}
}