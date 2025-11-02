<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\View\Events
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\View\Events;

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarFactoryInterface;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Sda\Component\Sdajem\Administrator\Model\EventsModel;

#use Joomla\CMS\Toolbar\ToolbarFactoryInterface;

class HtmlView extends BaseHtmlView
{
	/**
	 * An array of items
	 *
	 * @var  array
	 * @since 1.0.0
	 */
	protected $items;

	/**
	 * The model state
	 *
	 * @var  \JObject
	 * @since 1.0.0
	 */
	protected $state;

	/**
	 * Form object for search filters
	 *
	 * @var  \JForm
	 * @since 1.0.0
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var  array
	 * @since 1.0.0
	 */
	public $activeFilters;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 * @since   1.0.0
	 */
	public function display($tpl = null): void
	{
        /** @var EventsModel $model */
        $model = $this->getModel();

        $this->items = $model->getItems();
		$this->filterForm = $model->getFilterForm();;
		$this->activeFilters = $model->getActiveFilters();
		$this->state = $model->getState();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item) {
			$item->order_up = true;
			$item->order_dn = true;
		}

		if (!count($this->items) && $model->getIsEmptyState()) {
			$this->setLayout('emptystate');
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
			$this->sidebar = Sidebar::render();
		} else {
			// In article associations modal we need to remove language filter if forcing a language.
			// We also need to change the category filter to show show categories with All or the forced language.
			if ($forcedLanguage = Factory::getApplication()->input->get('forcedLanguage', '', 'CMD')) {
				// If the language is forced we can't allow to select the language, so transform the language selector filter into a hidden field.
				$languageXml = new \SimpleXMLElement('<field name="language" type="hidden" default="' . $forcedLanguage . '" />');
				$this->filterForm->setField($languageXml, 'filter', true);

				// Also, unset the active language filter so the search tools is not open by default with this filter.
				unset($this->activeFilters['language']);

			}
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 * @since   1.0.0
	 */
	protected function addToolbar()
	{
		$this->sidebar = Sidebar::render();

		$canDo = ContentHelper::getActions('com_sdajem', 'category', $this->state->get('filter.category_id'));
		$user  = Factory::getApplication()->getIdentity();

		// Get the toolbar object instance
		#$toolbar = Factory::getContainer()->get(ToolbarFactoryInterface::class)->createToolbar('toolbar');
		#$toolbar = Factory::getContainer()->get(ToolbarFactoryInterface::class)->createToolbar('toolbar');
        $toolbar = $this->getDocument()->getToolbar();

		ToolbarHelper::title(Text::_('COM_SDAJEM_EVENTS'), 'address event');

		if ($canDo->get('core.create') || count($user->getAuthorisedCategories('com_sdajem', 'core.create')) > 0) {
			$toolbar->addNew('event.add');
		}

		if ($canDo->get('core.edit.state')) {
			$dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('fa fa-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);
			$childBar = $dropdown->getChildToolbar();
			$childBar->publish('events.publish')->listCheck(true);
			$childBar->unpublish('events.unpublish')->listCheck(true);
			$childBar->archive('events.archive')->listCheck(true);

			if ($user->authorise('core.admin')) {
				$childBar->checkin('events.checkin')->listCheck(true);
			}

			if ($this->state->get('filter.published') != -2) {
				$childBar->trash('events.trash')->listCheck(true);
			}
		}

		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
			$toolbar->delete('events.delete')
				->text('JTOOLBAR_EMPTY_TRASH')
				->message('JGLOBAL_CONFIRM_DELETE')
				->listCheck(true);
		}

		if ($user->authorise('core.admin', 'com_sdajem') || $user->authorise('core.options', 'com_sdajem')) {
			$toolbar->preferences('com_sdajem');
		}
		#return $toolbar;
	}

}