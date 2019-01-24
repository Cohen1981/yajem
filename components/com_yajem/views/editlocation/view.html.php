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

use Joomla\CMS\MVC\View\HtmlView;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemViewEditlocation extends HtmlView
{
	/**
	 * @var state
	 * @since 1.0
	 */
	protected $state;

	/**
	 * @var location
	 * @since 1.0
	 */
	protected $location;

	/**
	 * @var form
	 * @since 1.0
	 */
	protected $form;

	/**
	 * @param   null $tpl Template to be used
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');

		// Note to self: Property always Item.
		$this->location = $this->get('Item');
		$this->form = $this->get('Form');

		JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'models');
		$modelAttachments = JModelLegacy::getInstance('attachments', 'YajemModel');
		$this->location->attachments = array();
		if ($this->location->id)
		{
			$this->location->attachments = $modelAttachments->getAttachments((int) $this->location->id, 'location');
		}
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		parent::display($tpl);

		YajemHelperAdmin::setDocument();
	}

}