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
use Joomla\CMS\Factory;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemControllerLocations extends AdminController
{
	/**
	 * @param   string $name    Location
	 * @param   string $prefix  YajemModel
	 * @param   array  $config  none
	 *
	 * @return boolean|JModelLegacy
	 *
	 * @since version 1.0
	 */
	public function getModel($name = 'Location', $prefix = 'YajemModel', $config = array('ignore-request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 *
	 *
	 * @since version 1.0
	 * @throws Exception
	 * @return void
	 *
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = Factory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		Factory::getApplication()->close();
	}
}
