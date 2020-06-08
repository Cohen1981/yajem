<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * @package     NAMESPACE
 *
 * @since       0.1.5
 */
class com_SdajemInstallerScript
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
		$config = array(
			'use_sdacontacts'=>'1',
			'usePlaningTool' =>'1',
			'eventDefaultView' =>'1',
			'viewToggle' =>'0',
			'filterDate' =>'1',
			'filterEventStatus' =>'1',
			'filterLocation' =>'1',
			'guestDefaultEventView' => 1
		);
		// Setting default config params
		if ($type == 'install')
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__extensions'));
			$defaults = json_encode($config);
			$query->set($db->quoteName('params') . ' = ' . $db->quote($defaults));
			$query->where($db->quoteName('name') . ' = ' . $db->quote('com_sdajem'));
			$db->setQuery($query);
			$db->execute();
		}

		if (! JFolder::exists(JPATH_ROOT . '/images/sdajem'))
		{
			JFolder::create(JPATH_ROOT . '/images/sdajem', $mode = 0755);
		}

		if (! JFolder::exists(JPATH_ROOT . '/images/sdajem/locations'))
		{
			JFolder::create(JPATH_ROOT . '/images/sdajem/locations', $mode = 0755);
		}

		if (! JFolder::exists(JPATH_ROOT . '/images/sdajem/events'))
		{
			JFolder::create(JPATH_ROOT . '/images/sdajem/events', $mode = 0755);
		}

		if ($type == 'update')
		{
			echo '<p>' . JText::_('COM_SDAJEM_UPDATE_TEXT') . $parent->get('manifest')->version . '</p>';
		}
	}
}