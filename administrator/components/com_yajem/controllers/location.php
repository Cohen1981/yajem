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

use Joomla\CMS\MVC\Controller\FormController;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemControllerLocation extends FormController
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_item = 'location';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_list = 'locations';

}
