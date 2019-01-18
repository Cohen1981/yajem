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
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemViewEvent extends HtmlView
{
	/**
	 * @var state
	 * @since 1.0
	 */
	protected $state;

	/**
	 * @var event
	 * @since 1.0
	 */
	protected $event;

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
		$this->event = $this->get('Item');
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

		$isNew = ($this->event->id == 0);

		ToolBarHelper::title(JText::_('COM_YAJEM_TITLE_EVENT'), 'item.png');

		ToolBarHelper::apply('event.apply', 'JTOOLBAR_APPLY');
		ToolBarHelper::save('event.save', 'JTOOLBAR_SAVE');

		ToolBarHelper::custom('event.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);

		if (!$isNew)
		{
			ToolBarHelper::custom('event.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}

		if (empty($this->event->id))
		{
			ToolBarHelper::cancel('event.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			ToolBarHelper::cancel('event.cancel', 'JTOOLBAR_CLOSE');
		}
	}

}