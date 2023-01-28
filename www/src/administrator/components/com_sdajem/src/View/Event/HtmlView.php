<?php
/** * @package     Sda\Component\Sdajem\Administrator\View\Events
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\View\Event;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use \Joomla\CMS\MVC\View\HtmlView AS BaseHtmlView;

class HtmlView extends BaseHtmlView
{
	/**
	 * The \JForm object
	 *
	 * @var  \JForm
	 */
	protected mixed $form;
	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected object $item;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 * @throws \Exception
	 */
	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->item = $this->get('Item');
		$this->addToolbar();
		return parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);
		$isNew = ($this->item->id == 0);
		ToolbarHelper::title($isNew ? Text::_('COM_SDAJEM_EVENT_NEW') : Text::_('COM_SDAJEM_EVENT_EDIT'), 'address foo');
		ToolbarHelper::apply('event.apply');
		ToolbarHelper::cancel('event.cancel', 'JTOOLBAR_CLOSE');
	}
}