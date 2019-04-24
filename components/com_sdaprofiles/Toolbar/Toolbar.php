<?php
/**
 * @package     Sda\Profiles\Site\Toolbar
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Profiles\Site\Toolbar;

use FOF30\Toolbar\Toolbar as BaseToolbar;
use FOF30\Container\Container;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Sda\Jem\Site\Model\Event;

/**
 * @package     Sda\Profiles\Site\Toolbar
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
	public function onProfilesBrowse()
	{
		ToolbarHelper::title(Text::_('COM_SDAPROFILES_TITLE_PROFILES_BROWSE'));

		if (Factory::getUser()->authorise('core.edit', 'com_sdaprofiles'))
		{
			ToolbarHelper::addNew();
		}
		if (Factory::getUser()->authorise('core.edit', 'groupprofile'))
		{
			ToolbarHelper::custom('newGroup', 'new', '', 'COM_SDAPROFILES_NEW_GROUPPROFILE', false);
		}
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onProfilesEdit()
	{
		ToolbarHelper::title(Text::_('COM_SDAPROFILES_TITLE_PROFILES_EDIT'));

		ToolbarHelper::apply();
		ToolbarHelper::save();
		ToolbarHelper::cancel();
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onProfilesAdd()
	{
		ToolbarHelper::title(Text::_('COM_SDAPROFILES_TITLE_PROFILES_EDIT'));

		ToolbarHelper::apply();
		ToolbarHelper::save();
		ToolbarHelper::cancel();
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onProfilesRead()
	{
		ToolbarHelper::title(Text::_('COM_SDAPROFILES_TITLE_PROFILES_READ'));

		if (Factory::getUser()->authorise('core.edit', 'groupprofile'))
		{
			ToolbarHelper::custom('editGroup', 'edit', '', 'COM_SDAPROFILES_EDIT_PROFILE', false);
		}

		ToolbarHelper::back();
	}
}
