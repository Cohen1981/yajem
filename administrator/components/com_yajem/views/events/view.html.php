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
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/');

/**
 * @package     Yajem
 *
 * @since       version
 */

class YajemViewEvents extends HtmlView
{
	/**
	 * @var events
	 * @since 1.0
	 */
	protected $items;

	/**
	 * @var pagination
	 * @since 1.0
	 */
	protected $pagination;

	/**
	 * @var state
	 * @since 1.0
	 */
	protected $state;

	/**
	 * @param   null $tpl Template to render
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	public function display($tpl = null)
	{
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');

		// Get the filter form
		$this->filterForm = $this->get('FilterForm');

		// Get active filters
		$this->activeFilters = $this->get('ActiveFilters');

		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		YajemHelperAdmin::addSubmenu('events');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();

		return parent::display($tpl);
	}

	/**
	 * Adding the toolbar
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = YajemHelperAdmin::getActions();

		JToolBarHelper::title(JText::_('YAJEM'), 'items.png');

		if ($canDo->get('core.create'))
		{
			JToolBarHelper::addNew('event.add', 'JTOOLBAR_NEW');
		}

		if (isset($this->items[0]))
		{
			if ($canDo->get('core.edit'))
			{
				JToolBarHelper::editList('event.edit', 'JTOOLBAR_EDIT');
			}

			if ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::divider();
				JToolbarHelper::publish('events.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('events.unpublish', 'JTOOLBAR_UNPUBLISH', true);

				JToolBarHelper::divider();
				JToolBarHelper::archiveList('events.archive', 'JTOOLBAR_ARCHIVE');
			}

		}

		if ($state->get('filter.published') == -2  && $canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', 'events.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::trash('events.trash', 'JTOOLBAR_TRASH');
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_yajem');
		}

	}

	/**
	 * Get sortable Fields
	 *
	 * @return array Array of sortable Fields
	 *
	 * @since 1.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering'    => JText::_('JGRID_HEADING_ORDERING'),
			'a.id'          => JText::_('JGRID_HEADING_ID'),
			'a.published'   => JText::_('JSTATUS'),
			'a.title'       => JText::_('COM_YAJEM_TITLE_EVENTS'),
			'a.catid'       => JText::_('COM_YAJEM_TITLE_EVENTS_CATID'),
			'hoster'        => JText::_('COM_YAJEM_TITLE_EVENTS_HOST'),
			'organizer'     => JText::_('COM_YAJEM_TITLE_EVENTS_ORGANIZER'),
			'a.startDate'   => JText::_('COM_YAJEM_TITLE_EVENTS_STARTDATE'),
			'a.endDate'     => JText::_('COM_YAJEM_TITLE_EVENTS_ENDDATE')
		);
	}
}
