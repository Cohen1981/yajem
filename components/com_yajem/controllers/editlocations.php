<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

defined('_JEXEC') or die;

// Base this Controller on the backend version.
JLoader::register('YajemControllerLocations', JPATH_ADMINISTRATOR . '/components/com_yajem/controllers/locations.php');

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemControllerEditlocations extends YajemControllerLocations
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_item = 'editlocation';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_list = 'locations';

}