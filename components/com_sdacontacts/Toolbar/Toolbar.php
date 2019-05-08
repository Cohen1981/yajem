<?php
/**
 * @package     Sda\Contacts\Site\Toolbar
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Contacts\Site\Toolbar;

use FOF30\Container\Container;
use FOF30\Toolbar\Toolbar as BaseToolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Sda\Contacts\Admin\Model\Contact;

/**
 * @package     Sda\Contacts\Site\Toolbar
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
	public function onContactsBrowse()
	{
		ToolbarHelper::title(Text::_('COM_SDACONTACTS_TITLE_CONTACTS_BROWSE'));

		if (Factory::getUser()->authorise('core.edit', 'com_sdacontacts'))
		{
			ToolbarHelper::addNew();
		}
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onContactsRead()
	{
		/** @var Contact $contact */
		$contact = Container::getInstance('com_sdacontacts')->factory->model('Contact');
		$contact->load();
		ToolbarHelper::title($contact->title);

		if (Factory::getUser()->authorise('core.edit', 'com_sdacontacts'))
		{
			ToolbarHelper::custom('edit', 'edit', '', 'JGLOBAL_EDIT', false);
		}

		ToolbarHelper::back();
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onContactsEdit()
	{
		ToolbarHelper::title(Text::_('COM_SDACONTACTS_TITLE_CONTACT_EDIT'));

		ToolbarHelper::save();
		ToolbarHelper::cancel();
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onContactsAdd()
	{
		ToolbarHelper::title(Text::_('COM_SDACONTACTS_TITLE_CONTACT_EDIT'));

		ToolbarHelper::save();
		ToolbarHelper::save2new('savenew');
		ToolbarHelper::cancel();
	}
}