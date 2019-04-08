<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

class YajemControllerProfile extends BaseController
{
	/**
	 *
	 * @return JControllerLegacy|void
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function view()
	{
		$id = Factory::getApplication()->input->request->get('id');
		$view = $this->getView('Profile', 'html');
		$model = $this->getModel('Profile');
		$model->setState('item.id', $id);
		$view->setModel($model);
		$view->display();
	}
}