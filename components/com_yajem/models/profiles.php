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

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Access\Access;
use Joomla\Component\Yajem\Administrator\Classes\YajemUserProfiles;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemModelProfiles extends ListModel
{
	/**
	 * YajemModelEvents constructor.
	 *
	 * @param   array $config Configuration array
	 *
	 * @since 1.0
	 */
	public function __construct($config = array())
	{
		$config['filter_fields'] = array(
			'name'
		);
		parent::__construct($config);
	}

	/**
	 * @param   null $ordering  column to order
	 * @param   null $direction direction
	 *
	 * @return void
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState($ordering, $direction);
	}

	/**
	 *
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getItems()
	{
		$profilesHelper = new YajemUserProfiles;

		$profiles = $profilesHelper->getProfiles();

		return $profiles;
	}
}
