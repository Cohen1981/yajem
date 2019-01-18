<?php
/**
 * @package     Yajem.Administrator
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\Utilities\ArrayHelper;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemControllerAttendees extends AdminController
{
	/**
	 * @param   string $name    Attendee
	 * @param   string $prefix  YajemModel
	 * @param   array  $config  none
	 *
	 * @return boolean|JModelLegacy
	 *
	 * @since 1.0
	 */
	public function getModel($name = 'Attendee', $prefix = 'YajemModel', $config = array('ignore-request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}
