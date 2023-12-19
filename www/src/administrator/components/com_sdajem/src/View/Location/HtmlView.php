<?php
/** * @package     Sda\Component\Sdajem\Administrator\View\Locations
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\View\Location;

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

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
	 * @since   1.0.0
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);

		$user = Factory::getApplication()->getIdentity();
		$userId = $user->id;

		$isNew = ($this->item->id == 0);

		ToolbarHelper::title($isNew ? Text::_('COM_SDAJEM_LOCATION_NEW') : Text::_('COM_SDAJEM_LOCATION_EDIT'), 'address location');

		// Since we don't track these assets at the item level, use the category id.
		$canDo = ContentHelper::getActions('com_sdajem', 'category', $this->item->catid);

		// Build the actions for new and existing records.
		if ($isNew) {
			// For new records, check the create permission.
			if ($isNew && $user->authorise('core.create', 'com_sdajem')) {
				ToolbarHelper::apply('location.apply');
				ToolbarHelper::saveGroup(
					[
						['save', 'location.save'],
						['save2new', 'location.save2new']
					],
					'btn-success'
				);
			}

			ToolbarHelper::cancel('location.cancel');
		} else {
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);
			$toolbarButtons = [];

			// Can't save the record if it's not editable
			if ($itemEditable) {
				ToolbarHelper::apply('location.apply');
				$toolbarButtons[] = ['save', 'location.save'];

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create')) {
					$toolbarButtons[] = ['save2new', 'location.save2new'];
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create')) {
				$toolbarButtons[] = ['save2copy', 'location.save2copy'];
			}

			ToolbarHelper::saveGroup(
				$toolbarButtons,
				'btn-success'
			);

			ToolbarHelper::cancel('location.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}