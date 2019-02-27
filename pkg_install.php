<?php

defined ( '_JEXEC' ) or die ();

/**
package installer script.
 */
class Pkg_YajemInstallerScript
{

	public function install($parent) {

		return true;
	}

	public function discover_install($parent) {
		return self::install($parent);
	}

	public function update($parent) {
		return self::install($parent);
	}

	public function uninstall($parent) {
		return true;
	}

	public function makeRoute($uri) {
		return JRoute::_($uri, false);
	}

	public function postflight($type, $parent) {
		// Clear Joomla system cache.
		/** @var JCache|JCacheController $cache */
		$cache = JFactory::getCache();
		$cache->clean('_system');

		if ($type == 'uninstall') return true;

		$this->enablePlugin('system', 'yajemLib');
		//$this->enablePlugin('yajem', 'mailer');

		return true;
	}

	function enablePlugin($group, $element)
	{
		$plugin = JTable::getInstance('extension');

		if (!$plugin->load(array('type' => 'plugin', 'folder' => $group, 'element' => $element)))
		{
			return false;
		}

		$plugin->enabled = 1;

		return $plugin->store();
	}

}
