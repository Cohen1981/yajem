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
use Joomla\CMS\Factory;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemViewLocation extends HtmlView
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
	 * @return mixed
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

		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Adding the toolbar
	 *
	 * @since 1.0
	 * @throws Exception
	 *
	 * @return void
	 *
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->location->id == 0);

		JToolBarHelper::title(JText::_('COM_YAJEM_TITLE_LOCATION'), 'item.png');

		JToolBarHelper::apply('location.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('location.save', 'JTOOLBAR_SAVE');

		JToolBarHelper::custom('location.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);

		if (!$isNew)
		{
			JToolBarHelper::custom('location.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}

		if (empty($this->location->id))
		{
			JToolBarHelper::cancel('location.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('location.cancel', 'JTOOLBAR_CLOSE');
		}
	}

}