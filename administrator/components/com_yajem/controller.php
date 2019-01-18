<?php
/**
 * @package    yajem
 *
 * @author     abahl <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * Yajem Controller.
 *
 * @package  yajem
 * @since    1.0
 */
class YajemController extends BaseController
{
	/**
	 * @param   bool    $cachable   default false
	 * @param   array   $urlparams  default empty
	 *
	 * @return JControllerLegacy
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function display($cachable = false, $urlparams = array())
	{
		$view = Factory::getApplication()->input->getCmd('view', 'events');
		Factory::getApplication()->input->set('view', $view);

		return parent::display($cachable, $urlparams);
	}
}
