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

defined('_JEXEC') or die;

/**
 * Yajem Controller.
 *
 * @package  yajem
 * @since    1.0
 */
class YajemController extends BaseController {
	/**
	 * @param   boolean $cachable   false
	 * @param   boolean $urlparams  false
	 *
	 * @return $this|JControllerLegacy
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$app  = JFactory::getApplication();
		$view = $app->input->getCmd('view', 'events');
		$app->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}
