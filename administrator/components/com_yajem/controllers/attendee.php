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
class YajemControllerAttendee extends FormController
{
	/**
	 * YajemControllerAttendee constructor.
	 *
	 * @param   array $config none
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	public function __construct(array $config = array())
	{
		$this->view_list = 'attendees';
		parent::__construct($config);
	}
}
