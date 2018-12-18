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
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemControllerEvent extends FormController
{
	/**
	 * YajemControllerEvent constructor.
	 *
	 * @param   array $config none
	 *
	 * @throws Exception
	 *
	 * @since version
	 */
	public function __construct(array $config = array())
	{
		$this->view_list = 'events';
		parent::__construct($config);
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 * Here used to trigger the yajem plugin mailer.
	 *
	 * @param  JModelLegacy  $model      The data model object.
	 * @param  array $validData The validated data.
	 *
	 * @return void
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	protected function postSaveHook(\JModelLegacy $model, $validData = array())
	{
		$isNew     = $model->getState('event.new');
		$id = $model->getState('event.id');

		// trigger all jem plugins
		PluginHelper::importPlugin('yajem');
		Factory::getApplication()->triggerEvent('onEventEdited', array($id, $isNew));

		// but show warning if mailer is disabled
		if (!PluginHelper::isEnabled('yajem', 'mailer')) {
			Factory::getApplication()->enqueueMessage(Text::_('COM_YAJEM_MAILPLUGIN_DISABLED'), 'warning');
		}
	}
}
