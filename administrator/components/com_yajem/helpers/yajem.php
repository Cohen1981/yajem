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
use Joomla\CMS\User\UserHelper;

defined('_JEXEC') or die;

/**
 * Yajem helper.
 *
 * @package     A package name
 * @since       1.0
 */
class YajemHelperAdmin
{
	/**
	 * Render submenu.
	 *
	 * @param   string  $vName  The name of the current view.
	 *
	 * @return  void.
	 *
	 * @since   1.0
	 */
	public function addSubmenu($vName)
	{
		#HtmlHelperSidebar::addEntry(Text::_('COM_YAJEM'), 'index.php?option=com_yajem&view=yajem', $vName == 'yajem');
		JHtmlSidebar::addEntry(
			JText::_('COM_YAJEM_SUBMENU_EVENTS'),
			'index.php?option=com_yajem&view=events',
			$vName == 'events'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_YAJEM_SUBMENU_LOCATIONS'),
			'index.php?option=com_yajem&view=locations',
			$vName == 'locations'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_YAJEM_SUBMENU_ATTENDEES'),
			'index.php?option=com_yajem&view=attendees',
			$vName == 'attendees'
		);

		JHtmlSidebar::addEntry(
			JText::_('JCATEGORIES') . ' (Yajem)',
			"index.php?option=com_categories&extension=com_yajem&view=categories",
			$vName == 'categories'
		);
	}

	public function getUserList() {
		$db     = Factory::getDbo();
		$query  = $db->getQuery(true);

		$query->select('id')->from('#__users');

		$userIds = $db->setQuery($query)->loadColumn();

		foreach ($userIds as $userId) {
			$profiles[$userId] = YajemHelperAdmin::getUser($userId);
		}

		return $profiles;
	}

	/**
	 * @param $userId
	 *
	 * @return mixed
	 *
	 * @since 1.2.0
	 */
	public function getUser($userId) {

		$PROFILEKEYS = array("id", "name", "username", "email", "avatar");

		$user = (array) Factory::getUser( $userId );
		foreach ($user as $k => $v) {
			if (in_array($k, $PROFILEKEYS, true))
			{
				$profile[$k] = $user[$k] = $v;
			}
		}

		$userProfile = (array) UserHelper::getProfile( $userId );
		foreach ($userProfile['profile'] as $k => $v) {
			if (in_array($k, $PROFILEKEYS, true))
			{
				$profile[$k] = $userProfile['profile'][$k] = $v;
			}
		}

		foreach ($userProfile['profileYajem'] as $k => $v) {
			if (in_array($k, $PROFILEKEYS, true))
			{
				$profile[$k] = $userProfile['profileYajem'][$k] = $v;
			}
		}

		if (!$profile['name']) {
			$profile['name'] = $profile['username'];
		}

		return $profile;
	}

	/**
	 * Get a list of Actions that can be performed
	 *
	 * @return JObject
	 *
	 * @since 1.0
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_yajem';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}

	/**
	 * Get the link to a location
	 * @param   string $id      location id
	 * @param   string $catid   category id of location
	 *
	 * @return string
	 *
	 * @since 1.0
	 */
	public static function getLocationRoute($id, $catid)
	{
		// Create the link
		$link = 'index.php?option=com_contact&view=contact&id=' . $id;

		if ($catid > 1)
		{
			$link .= '&catid=' . $catid;
		}

		return $link;
	}

	/**
	 * Method to set up the html document properties
	 *
	 * @return void
	 *
	 * @since 1.1
	 */
	public static function setDocument()
	{
		$document = Factory::getDocument();
		$document->addScript(JUri::root() . 'media/com_yajem/js/event.js');
		$document->addStyleSheet(JUri::root() . 'media/com_yajem/css/style.css');
	}
}
/**
 * Joomla's way to add SubMenus in categoris view
 * @package     Yajem
 *
 * @since       version
 */
class YajemHelper extends YajemHelperAdmin
{
}
