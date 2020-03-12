<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * @package     NAMESPACE
 *
 * @since       0.1.5
 */
class com_SdaprofilesInstallerScript
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
		// Setting default config params
		if ($type == 'install')
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__extensions'));
			$defaults = '{"show_attendings_all":"0","show_organizing_all":"0"}';
			$query->set($db->quoteName('params') . ' = ' . $db->quote($defaults));
			$query->where($db->quoteName('name') . ' = ' . $db->quote('com_sdaprofiles'));
			$db->setQuery($query);
			$db->execute();

			if (! JFolder::exists(JPATH_ROOT . '/images/Ausruestung'))
			{
				JFolder::create(JPATH_ROOT . '/images/Ausruestung', $mode = 0755);

				/*
				No Type hinting. So copied from joomla api
				files(string $path,
					string $filter = '.',
					mixed $recurse = false,
					boolean $full = false,
					array $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'),
					array $excludefilter = array('^\..*', '.*~'),
					boolean $naturalSort = false) : array
				*/
				$images = JFolder::files(JPATH_ROOT . '/media/com_sdaprofiles/images/equipment', '', false, true);
				$imageNames = JFolder::files(JPATH_ROOT . '/media/com_sdaprofiles/images/equipment', '', false, false);

				for ($i = 0; $i < count($images); $i++)
				{
					JFile::copy($images[$i], JPATH_ROOT . '/images/Ausruestung/' . $imageNames[$i]);
				}
			}

			if (! JFolder::exists(JPATH_ROOT . '/images/Avatare'))
			{
				JFolder::create(JPATH_ROOT . '/images/Avatare', $mode = 0755);
			}
		}

		if ($type == 'update')
		{
			echo '<p>' . JText::_('COM_SDAPROFILES_UPDATE_TEXT') . $parent->get('manifest')->version . '</p>';
		}
	}
}