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
use Yajem\User\YajemUserProfiles;

/**
 *
 * @since       version
 */
class YajemModelProfiles extends ListModel
{
	/**
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	public function getItems()
	{
		$profiles = new YajemUserProfiles;

		return $profiles->getProfiles();
	}
}