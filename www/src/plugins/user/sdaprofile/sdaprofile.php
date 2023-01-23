<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  User.sdaprofile
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt

 * @phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
 */

use Joomla\CMS\Plugin\CMSPlugin;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * An example custom profile plugin.
 *
 * @since  1.6
 */
class PlgUserSdaprofile extends CMSPlugin
{
	/**
	 * @var    \Joomla\CMS\Application\CMSApplication
	 *
	 * @since  4.0.0
	 */
	protected $app;

	/**
	 * @var    \Joomla\Database\DatabaseDriver
	 *
	 * @since  4.0.0
	 */
	protected $db;

	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 *
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

}
