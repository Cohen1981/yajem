<?php

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

/**
 * Class plgSystemSdaInstallerScript
 * @since 1.0.0
 */
class plgSystemSdaInstallerScript {

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
	}

	/**
	 * This method is called after a component is uninstalled.
	 *
	 * @param   \stdClass $parent - Parent object calling this method.
	 *
	 * @return void
	 *
	 * @since   1.0
	 */
	public function uninstall($parent)
	{
	}

	/**
	 * This method is called after a component is updated.
	 *
	 * @param   \stdClass $parent - Parent object calling object.
	 *
	 * @return void
	 *
	 * @since   1.0
	 */
	public function update($parent)
	{
	}

	/**
	 * Runs just before any installation action is preformed on the component.
	 * Verifications and pre-requisites should run in this function.
	 *
	 * @param   string    $type   - Type of PreFlight action. Possible values are:
	 *                           - * install
	 *                           - * update
	 *                           - * discover_install
	 * @param   \stdClass $parent - Parent object calling object.
	 *
	 * @return void
	 *
	 * @since   1.0
	 */
	public function preflight($type, $parent)
	{
	}

	/**
	 * Runs right after any installation action is preformed on the component.
	 *
	 * @param   string    $type   - Type of PostFlight action. Possible values are:
	 *                            - * install
	 *                            - * update
	 *                            - * discover_install
	 * @param   \stdClass $parent - Parent object calling object.
	 *
	 * @return void
	 *
	 * @since   1.0
	 */
	public function postflight($type, $parent)
	{
		// Setting default config params
		if ($type == 'install')
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__extensions'));
			$query->set($db->quoteName('enabled') . ' = 1');
			$query->where($db->quoteName('element') . ' = ' . $db->quote('sdalib'))
				  ->andWhere($db->quoteName('folder') . ' = ' . $db->quote('system'), 'AND');
			$db->setQuery($query);
			$db->execute();
		}

		if ($type == 'update')
		{
			echo '<p>' . Text::_('COM_SDAJEM_UPDATE_TEXT') . $parent->get('manifest')->version . '</p>';
		}
	}
}