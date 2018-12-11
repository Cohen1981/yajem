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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');

/**
 * @package     Yajem
 *
 * @since       version
 */

class YajemViewAttendees extends HtmlView
{
	/**
	 * @var attendees
	 * @since version
	 */
	protected $items;

	/**
	 * @var pagination
	 * @since version
	 */
	protected $pagination;

	/**
	 * @var state
	 * @since version
	 */
	protected $state;

	/**
	 * @param   null $tpl Template to render
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 *
	 * @since version
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

		YajemHelperAdmin::addSubmenu('attendees');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();

		return parent::display($tpl);
	}

	/**
	 * Adding the toolbar
	 *
	 * @since version
	 *
	 * @return void
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = YajemHelperAdmin::getActions();

		JToolBarHelper::title(JText::_('YAJEM'), 'items.png');

		if (isset($this->items[0]))
		{
			if ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::archiveList('attendees.archive', 'JTOOLBAR_ARCHIVE');
				JToolBarHelper::divider();
			}
		}

		if ($state->get('filter.published') == -2  && $canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', 'attendees.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::trash('attendees.trash', 'JTOOLBAR_TRASH');
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
	 * @since version
	 */
	protected function getSortFields()
	{
		return array(
			'a.id'      => JText::_('JGRID_HEADING_ORDERING'),
			'a.attendeeId' => JText::_('COM_YAJEM_TITLE_EVENT_ID'),
			'a.userId'  => JText::_('COM_YAJEM_TITLE_USERID'),
			'attendee'     => JText::_('COM_YAJEM_TITLE_EVENTS'),
			'attendee'  => JText::_('COM_YAJEM_TITLE_ATTENDEE')
		);
	}
}
