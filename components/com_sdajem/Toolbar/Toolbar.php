<?php
/**
 * @package     Sda\Jem\Site\Toolbar
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Site\Toolbar;

use FOF30\Toolbar\Toolbar as BaseToolbar;
use FOF30\Container\Container;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Sda\Jem\Site\Model\Event;
use Sda\Jem\Site\Model\Location;

/**
 * @package     Sda\Jem\Site\Toolbar
 *
 * @since       0.0.1
 */
class Toolbar extends BaseToolbar
{
	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onEventsRead()
	{
		/** @var Event $event */
		$event = Container::getInstance('com_sdajem')->factory->model('Event');
		$event->load();

		ToolbarHelper::title(' ' . $event->title, 'calendar');

		if (Factory::getUser()->authorise('core.edit', 'com_sdaprofiles'))
		{
			ToolbarHelper::custom('edit', 'edit', '', 'JGLOBAL_EDIT', false);
		}

		ToolbarHelper::custom('cancel', 'backward-2', '', 'COM_SDAJEM_BACK', false);
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onEventsBrowse()
	{
		ToolbarHelper::title(Text::_('COM_SDAJEM_TITLE_EVENTS_BROWSE'));

		if (Factory::getUser()->authorise('core.edit', 'com_sdaprofiles'))
		{
			ToolbarHelper::addNew();
			ToolbarHelper::custom('addNewLocation', 'new', '', 'COM_SDAJEM_LOCATION_NEW', false);
		}
	}

	/**
	 * @return void
	 *
	 * @since 0.2.8
	 */
	public function onLocationsBrowse()
	{
		ToolbarHelper::title(Text::_('COM_SDAJEM_TITLE_LOCATIONS_BROWSE'));
		if (Factory::getUser()->authorise('core.edit', 'com_sdaprofiles'))
		{
			ToolbarHelper::addNew();
		}
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onEventsAdd()
	{
		ToolbarHelper::title(Text::_('COM_SDAJEM_TITLE_EVENTS_ADD'));

		ToolbarHelper::apply();
		ToolbarHelper::save();
		ToolbarHelper::cancel();

		ToolbarHelper::custom('addNewLocation', 'new', '', 'COM_SDAJEM_LOCATION_NEW', false);
		ToolbarHelper::custom('addNewCategory', 'new', '', 'COM_SDAJEM_CATEGORY_NEW', false);
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onEventsEdit()
	{
		ToolbarHelper::title(Text::_('COM_SDAJEM_TITLE_EVENTS_EDIT'));

		ToolbarHelper::apply();
		ToolbarHelper::save();
		ToolbarHelper::cancel();

		ToolbarHelper::custom('addNewCategory', 'new', '', 'COM_SDAJEM_CATEGORY_NEW', false);
		ToolbarHelper::spacer('20');
		ToolbarHelper::custom('archiveEvent', 'archive', '', 'JTOOLBAR_ARCHIVE', false);
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onLocationsAdd()
	{
		ToolbarHelper::title(Text::_('COM_SDAJEM_TITLE_LOCATIONS_ADD'));

		ToolbarHelper::apply();
		ToolbarHelper::save();
		ToolbarHelper::cancel();

		ToolbarHelper::custom('addNewCategory', 'new', '', 'COM_SDAJEM_CATEGORY_NEW', false);
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onLocationsEdit()
	{
		ToolbarHelper::title(Text::_('COM_SDAJEM_TITLE_LOCATIONS_EDIT'));

		ToolbarHelper::apply();
		ToolbarHelper::save();
		ToolbarHelper::cancel();

		ToolbarHelper::custom('addNewCategory', 'new', '', 'COM_SDAJEM_CATEGORY_NEW', false);
	}

	/**
	 * @return void
	 *
	 * @since 0.2.8
	 */
	public function onLocationsRead()
	{
		ToolbarHelper::back();
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onCategoriesAdd()
	{
		ToolbarHelper::title(Text::_('COM_SDAJEM_TITLE_CATEGORY_ADD'));

		ToolbarHelper::apply();
		ToolbarHelper::save();
		ToolbarHelper::cancel();
	}
}
