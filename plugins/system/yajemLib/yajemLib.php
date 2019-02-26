<?php
/**
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

/**
 * Mylib plugin class.
 *
 * @package     Joomla.plugin
 * @subpackage  System.mylib
 * @since 1.3.0
 */
class plgSystemYajemLib extends JPlugin
{
	/**
	 * Method to register custom library.
	 *
	 * @return  void
	 * @since 1.3.0
	 */
	public function onAfterInitialise()
	{
		JLoader::registerNamespace('Yajem',
			JPATH_SITE . '/libraries/yajem',
			false,
			false,
			'psr4'
		);
	}
}