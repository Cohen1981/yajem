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
}
