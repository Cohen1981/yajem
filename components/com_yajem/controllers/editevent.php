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

// Base this Controller on the backend version.
JLoader::register('YajemControllerEvent', JPATH_ADMINISTRATOR . '/components/com_yajem/controllers/event.php');

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemControllerEditevent extends YajemControllerEvent
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_item = 'editevent';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_list = 'events';

}
