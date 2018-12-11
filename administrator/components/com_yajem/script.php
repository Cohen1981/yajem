<?php
/**
 * @package    yajem
 *
 * @author     abahl <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * Script file of Yajem component.
 *
 * The name of this class is dependent on the component being installed.
 * The class name should have the component's name, directly followed by
 * the text InstallerScript (ex:. com_helloWorldInstallerScript).
 *
 * This class will be called by Joomla!'s installer, if specified in your component's
 * manifest file, and is used for custom automation actions in its installation process.
 *
 * In order to use this automation script, you should reference it in your component's
 * manifest file as follows:
 * <scriptfile>script.php</scriptfile>
 *
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */
class com_helloWorldInstallerScript
{
	/**
	 * This method is called after a component is installed.
	 *
	 * @param   \stdClass $parent - Parent object calling this method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function install($parent)
	{
		$parent->getParent()->setRedirectURL('index.php?option=com_helloworld');
	}

	/**
	 * This method is called after a component is uninstalled.
	 *
	 * @param  \stdClass $#parent - Parent object calling this method.
	 *
	 * @return void
	 *
	 * @since   1.0
	 */
	public function uninstall($parent)
	{
		#echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
	}

	/**
	 * This method is called after a component is updated.
	 *
	 * @param  \stdClass $parent - Parent object calling object.
	 *
	 * @return void
	 *
	 * @since   1.0
	 */
	public function update($parent)
	{
		#echo '<p>' . JText::sprintf('COM_HELLOWORLD_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
	}

	/**
	 * Runs just before any installation action is preformed on the component.
	 * Verifications and pre-requisites should run in this function.
	 *
	 * @param  string    $type   - Type of PreFlight action. Possible values are:
	 *                           - * install
	 *                           - * update
	 *                           - * discover_install
	 * @param  \stdClass $parent - Parent object calling object.
	 *
	 * @return void
	 *
	 * @since   1.0
	 */
	public function preflight($type, $parent)
	{
		#echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	/**
	 * Runs right after any installation action is preformed on the component.
	 *
	 * @param  string    $type   - Type of PostFlight action. Possible values are:
	 *                           - * install
	 *                           - * update
	 *                           - * discover_install
	 * @param  \stdClass $parent - Parent object calling object.
	 *
	 * @return void
	 *
	 * @since   1.0
	 */
	function postflight($type, $parent)
	{
		// Setting default config params
		if ($type == 'install')
		{
			$db = Factory::getDBO();
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__extensions'));
			$defaults = '{"use_modal_location":"1",
							"use_location_contact":"0",
							"use_host":"1",
							"use_organizer":"1",
							"show_pastEvents":"0"
							}';
			$query->set($db->quoteName('params') . ' = ' . $db->quote($defaults));
			$query->where($db->quoteName('name') . ' = ' . $db->quote('com_yajem'));
			$db->setQuery($query);
			$db->execute();
		}
	}
}