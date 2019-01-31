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
 * @package     Yajem.Administrator
 *
 * @since       1.2.0
 */
class YajemControllerComment extends FormController
{
	/**
	 * YajemControllerComment constructor.
	 *
	 * @param   array $config Config array
	 *
	 * @throws Exception
	 * @since 1.2.0
	 */
	public function __construct(array $config = array())
	{
		parent::__construct($config);
	}

	/**
	 * @param   JModelLegacy $model     The Model
	 * @param   array        $validData Data
	 *
	 * @return integer
	 * @since 1.2.0
	 */
	protected function postSaveHook(\JModelLegacy $model, $validData = array())
	{
		// Frontend differs from backend model by name
		$modelname = $model->get('name');

		// Get needed params from the model
		$id = $model->getState($modelname . '.id');

		return $id;
	}
}