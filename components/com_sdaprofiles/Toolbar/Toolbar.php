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
use Sda\Profiles\Site\Model\Profile;

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

		if (Factory::getUser()->authorise('core.add', 'com_sdaprofiles'))
		{
			ToolbarHelper::addNew();
		}

		if (Factory::getUser()->authorise('core.edit', 'com_sdaprofiles'))
		{
			ToolbarHelper::custom('ownProfile', 'new', '', 'COM_SDAPROFILES_OWN_PROFILE', false);
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

		//ToolbarHelper::apply();
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

		// ToolbarHelper::apply();
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
		$user = Factory::getUser();

		ToolbarHelper::title(Text::_('COM_SDAPROFILES_TITLE_PROFILES_READ'));

		/** @var Profile $profile */
		$profile = $this->container->factory->model('Profile');
		$profile->load();

		if ($user->authorise('core.admin') ||
			($user->authorise('core.edit', 'groupprofile') && ($user->id === $profile->users_user_id || $profile->users_user_id == '')))
		{
			ToolbarHelper::custom('editProfile', 'edit', '', 'COM_SDAPROFILES_EDIT_PROFILE', false);
		}

		ToolbarHelper::back();
	}

	/**
	 * @return void
	 *
	 * @since 0.1.5
	 */
	public function onFittingTypesBrowse()
	{
		ToolbarHelper::title(Text::_('COM_SDAPROFILES_TITLE_FITTINGTYPES_BROWSE'));

		if (Factory::getUser()->authorise('core.edit', 'com_sdaprofiles'))
		{
			ToolbarHelper::addNew();
			ToolbarHelper::editList();
			ToolbarHelper::deleteList();
		}
	}

	/**
	 * @return void
	 *
	 * @since 0.1.5
	 */
	public function onFittingTypesAdd()
	{
		ToolbarHelper::title(Text::_('COM_SDAPROFILES_TITLE_FITTINGTYPES_EDIT'));
		// ToolbarHelper::apply();
		ToolbarHelper::save();
		ToolbarHelper::save2new('savenew');
		ToolbarHelper::cancel();
	}

	/**
	 * @return void
	 *
	 * @since 0.1.5
	 */
	public function onFittingTypesEdit()
	{
		ToolbarHelper::title(Text::_('COM_SDAPROFILES_TITLE_FITTINGTYPES_EDIT'));
		// ToolbarHelper::apply();
		ToolbarHelper::save();
		ToolbarHelper::save2new('savenew');

		ToolbarHelper::cancel();
	}

	/**
	 * @return void
	 *
	 * @since 0.1.6
	 */
	public function onFittingImagesBrowse()
	{
		ToolbarHelper::title(Text::_('COM_SDAPROFILES_TITLE_FITTINGIMAGES_BROWSE'));

		if (Factory::getUser()->authorise('core.edit', 'com_sdaprofiles'))
		{
			ToolbarHelper::addNew();
			ToolbarHelper::editList();
			ToolbarHelper::deleteList();
		}
	}

	/**
	 * @return void
	 *
	 * @since 0.1.6
	 */
	public function onFittingImagesEdit()
	{
		ToolbarHelper::title(Text::_('COM_SDAPROFILES_TITLE_FITTINGIMAGES_EDIT'));
		ToolbarHelper::save();
		ToolbarHelper::save2new('savenew');
		ToolbarHelper::cancel();
	}

	/**
	 * @return void
	 *
	 * @since 0.1.7
	 */
	public function onFittingImagesAdd()
	{
		ToolbarHelper::title(Text::_('COM_SDAPROFILES_TITLE_FITTINGIMAGES_ADD'));
		ToolbarHelper::save();
		ToolbarHelper::save2new('savenew');
		ToolbarHelper::cancel();
	}

	/**
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function onFittingsBrowse()
	{
		ToolbarHelper::title(Text::_('COM_SDAPROFILES_TITLE_FITTINGS_BROWSE'));
		ToolbarHelper::cancel();
	}
}
