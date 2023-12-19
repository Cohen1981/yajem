<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_sdajem
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;

/**
 * Script file of Sdajem Component
 *
 * @since  1.0.0
 */
class Com_SdajemInstallerScript
{
	/**
	 * Minimum Joomla version to check
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	private $minimumJoomlaVersion = '4.0';
	/**
	 * Minimum PHP version to check
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	private $minimumPHPVersion = '8.1';
	/**
	 * Method to install the extension
	 *
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  1.0.0
	 */
	public function install($parent): bool
	{
		echo Text::_('COM_SDAJEM_INSTALLERSCRIPT_INSTALL');

		return true;
	}
	/**
	 * Method to uninstall the extension
	 *
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  1.0.0
	 */
	public function uninstall($parent): bool
	{
		echo Text::_('COM_SDAJEM_INSTALLERSCRIPT_UNINSTALL');
		return true;
	}
	/**
	 * Method to update the extension
	 *
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  1.0.0
	 *
	 */
	public function update($parent): bool
	{
		echo Text::_('COM_SDAJEM_INSTALLERSCRIPT_UPDATE');
		return true;
	}
	/**
	 * Function called before extension installation/update/removal procedure commences
	 *
	 * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  1.0.0
	 *
	 * @throws Exception
	 */
	public function preflight($type, $parent): bool
	{
		if ($type !== 'uninstall') {
			// Check for the minimum PHP version before continuing
			if (!empty($this->minimumPHPVersion) && version_compare(PHP_VERSION, $this->minimumPHPVersion, '<')) {
				Log::add(
					Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPHPVersion),
					Log::WARNING,
					'jerror'
				);
				return false;
			}
			// Check for the minimum Joomla version before continuing
			if (!empty($this->minimumJoomlaVersion) && version_compare(JVERSION, $this->minimumJoomlaVersion, '<')) {
				Log::add(
					Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomlaVersion),
					Log::WARNING,
					'jerror'
				);
				return false;
			}
		}
		echo Text::_('COM_SDAJEM_INSTALLERSCRIPT_PREFLIGHT');
		return true;
	}
	/**
	 * Function called after extension installation/update/removal procedure commences
	 *
	 * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  1.0.0
	 *
	 */
	public function postflight($type, $parent)
	{
		echo Text::_('COM_SDAJEM_INSTALLERSCRIPT_POSTFLIGHT');

		$assetId = $this->getAssetId();

		// Initialize a new category.
		$category = Table::getInstance('Category');

		//$this->setupData($category, 'com_sdajem.events', 'Märkte', ApplicationHelper::stringURLSafe('EventMärkte'), $assetId);
		//$this->setupData($category, 'com_sdajem.locations', 'Orte', ApplicationHelper::stringURLSafe('EventOrte'), $assetId);
		return true;
	}

	private function getAdminId()
	{
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$query = $db->getQuery(true);
		// Select the admin user ID
		$query
			->clear()
			->select($db->quoteName('u') . '.' . $db->quoteName('id'))
			->from($db->quoteName('#__users', 'u'))
			->join(
				'LEFT',
				$db->quoteName('#__user_usergroup_map', 'map')
				. ' ON ' . $db->quoteName('map') . '.' . $db->quoteName('user_id')
				. ' = ' . $db->quoteName('u') . '.' . $db->quoteName('id')
			)
			->join(
				'LEFT',
				$db->quoteName('#__usergroups', 'g')
				. ' ON ' . $db->quoteName('map') . '.' . $db->quoteName('group_id')
				. ' = ' . $db->quoteName('g') . '.' . $db->quoteName('id')
			)
			->where(
				$db->quoteName('g') . '.' . $db->quoteName('title')
				. ' = ' . $db->quote('Super Users')
			);
		$db->setQuery($query);
		$id = $db->loadResult();
		if (!$id || $id instanceof \Exception)
		{
			return false;
		}
		return $id;
	}

	/**
	 * @param Table     $table
	 * @param string    $extension extension for the category
	 * @param string    $title
	 * @param string    $alias
	 *
	 * @return false|void
	 *
	 * @since 1.0.0
	 */
	private function setupData($table, $extension, $title, $alias, $assetId) {
		$data = array(
			'extension' => $extension,
			'title' => $title,
			'alias' => $alias . '(de-DE)',
			'description' => '',
			'published' => 1,
			'access' => 1,
			'params' => '{"target":"","image":""}',
			'metadesc' => '',
			'metakey' => '',
			'metadata' => '{"page_title":"","author":"","robots":""}',
			'created_time' => Factory::getDate()->toSql(),
			'created_user_id' => (int) $this->getAdminId(),
			'language' => '*',
			'rules' => array(),
			'parent_id' => $assetId,
		);
		$table->setLocation(1, 'last-child');
		// Bind the data to the table
		if (!$table->bind($data))
		{
			return false;
		}
		// Check to make sure our data is valid.
		if (!$table->check())
		{
			return false;
		}
		// Store the category.
		if (!$table->store(true))
		{
			return false;
		}
		return true;
	}

	private function getAssetId()
	{
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$query = $db->getQuery(true);
		// Select the admin user ID
		$query
			->clear()
			->select($db->quoteName('id'))
			->from($db->quoteName('#__assets'))
			->where(
				$db->quoteName('name')
				. ' = ' . $db->quote('com_sdajem')
			);
		$db->setQuery($query);
		$id = $db->loadResult();
		if (!$id || $id instanceof \Exception)
		{
			return false;
		}
		return $id;
	}
}
