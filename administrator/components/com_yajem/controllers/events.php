<?php
/**
 * @package    yajem
 *
 * @author     abahl <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or die;

/**
 * Yajem Controller.
 *
 * @package  yajem
 * @since    1.0
 */
class YajemControllerEvents extends AdminController
{
	/**
	 * @param   string $name   Event
	 * @param   string $prefix YajemModel
	 * @param   array  $config none
	 *
	 * @return boolean|JModelLegacy
	 *
	 * @since version 1.0
	 */
	public function getModel($name = 'Event', $prefix = 'YajemModel', $config = array('ignore-request' => true))
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