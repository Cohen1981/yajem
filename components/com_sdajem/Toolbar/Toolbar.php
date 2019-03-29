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
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

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
		ToolbarHelper::title(Text::_('COM_SDAJEM_TITLE_EVENTS_READ'));

		if (Factory::getUser()->authorise('core.edit', 'com_sdaprofiles'))
		{
			ToolbarHelper::custom('edit', 'edit', '', 'JGLOBAL_EDIT', false);
		}

		ToolbarHelper::custom('cancel', 'back', '', 'COM_SDAJEM_BACK', false);
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