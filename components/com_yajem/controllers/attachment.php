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
use Joomla\CMS\Factory;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemControllerAttachment extends FormController
{
	/**
	 * YajemControllerEvent constructor.
	 *
	 * @param   array $config none
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	public function __construct(array $config = array())
	{
		$this->view_list = 'attachments';
		parent::__construct($config);
	}

	/**
	 * Delete a given Attachment
	 *
	 * @return bool
	 *
	 * @since 1.1
	 * @throws Exception
	 */
	public function deleteAttachment() {
		$id = Factory::getApplication()->input->get('id');
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components/com_yajem/models');
		$modelAttachment = JModelLegacy::getInstance('Attachment','YajemModel');
		$modelAttachment->delete($id);
		return true;
	}
}
