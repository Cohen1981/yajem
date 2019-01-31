<?php
/**
 * @version
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * An example custom profile plugin.
 *
 * @package		Joomla.Plugins
 * @subpackage	user.profile
 *
 * @since 1.2.0
 */
class plgUserProfileYajem extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * @param	string $context	The context for the data
	 * @param	int	   $data	The user id
	 *
	 * @return	boolean
	 * @since	1.2.0
	 */
	public function onContentPrepareData($context, $data)
	{
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_users.profile','com_users.registration','com_users.user','com_admin.profile')))
		{
			return true;
		}

		$userId = isset($data->id) ? $data->id : 0;

		// Load the profile data from the database.
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT profile_key, profile_value FROM #__user_profiles' .
			' WHERE user_id = ' . (int) $userId .
			' AND profile_key LIKE \'profileYajem.%\'' .
			' ORDER BY ordering'
		);
		$results = $db->loadRowList();

		// Merge the profile data.
		$data->profileYajem = array();

		foreach ($results as $v)
		{
			$k = str_replace('profileYajem.', '', $v[0]);
			$data->profileYajem[$k] = json_decode($v[1], true);
		}

		return true;
	}

	/**
	 * @param	JForm	$form The form to be altered.
	 * @param	array	$data The associated data for the form.
	 * @return	boolean
	 * @since	1.2.0
	 */
	public function onContentPrepareForm($form, $data)
	{
		// Load user_profile plugin language
		$lang = JFactory::getLanguage();
		$lang->load('plg_user_profileYajem', __DIR__);

		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');

			return false;
		}

		// Check we are manipulating a valid form.
		if (!in_array($form->getName(), array('com_users.profile', 'com_users.registration','com_users.user','com_admin.profile')))
		{
			return true;
		}

		if ($form->getName() == 'com_users.profile')
		{
			// Add the profile fields to the form.
			JForm::addFormPath(dirname(__FILE__) . '/profiles');
			$form->loadFile('profile', false);

			// Toggle whether the something field is required.
			if ($this->params->get('profile-require_something', 1) > 0)
			{
				$form->setFieldAttribute('something', 'required', $this->params->get('profile-require_something') == 2, 'profileYajem');
			}
			else
			{
				$form->removeField('something', 'profileYajem');
			}
		}

		// In this example, we treat the frontend registration and the back end user create or edit as the same.
		elseif ($form->getName() == 'com_users.registration' || $form->getName() == 'com_users.user')
		{
			// Add the registration fields to the form.
			JForm::addFormPath(dirname(__FILE__) . '/profiles');
			$form->loadFile('profile', false);

			// Toggle whether the something field is required.
			if ($this->params->get('register-require_something', 1) > 0)
			{
				$form->setFieldAttribute('something', 'required', $this->params->get('register-require_something') == 2, 'profileYajem');
			}
			else
			{
				$form->removeField('something', 'profileYajem');
			}
		}
	}

	/**
	 * @param   array       $data   The form data
	 * @param   boolean     $isNew  New entry
	 * @param   boolean     $result Boolean
	 * @param   \Exception  $error  Not used
	 *
	 * @return boolean
	 *
	 * @since version
	 */
	public function onUserAfterSave($data, $isNew, $result, $error)
	{
		$userId	= ArrayHelper::getValue($data, 'id', 0, 'int');

		if ($userId && $result && isset($data['profileYajem']) && (count($data['profileYajem'])))
		{
			try
			{
				$db = JFactory::getDbo();
				$db->setQuery('DELETE FROM #__user_profiles WHERE user_id = ' . $userId . ' AND profile_key LIKE \'profileYajem.%\'');

				if (!$db->execute())
				{
					throw new Exception;
				}

				$tuples = array();
				$order	= 1;

				foreach ($data['profileYajem'] as $k => $v)
				{
					$tuples[] = '(' . $userId . ', ' . $db->quote('profileYajem.' . $k) .
						', ' . $db->quote(json_encode($v)) . ', ' . ($order++) . ')';
				}

				$db->setQuery('INSERT INTO #__user_profiles VALUES ' . implode(', ', $tuples));

				if (!$db->execute())
				{
					throw new Exception;
				}
			}

			catch (Exception $e)
			{
				$this->_subject->setError($e->getMessage());

				return false;
			}
		}

		return true;
	}

	/**
	 * Remove all user profile information for the given user ID
	 *
	 * Method is called after user data is deleted from the database
	 *
	 * @param	array		$user		Holds the user data
	 * @param	boolean		$success	True if user was succesfully stored in the database
	 * @param	string		$msg		Message
	 *
	 * @return boolean
	 *
	 * @since 1.2.0
	 */
	public function onUserAfterDelete($user, $success, $msg)
	{
		if (!$success)
		{
			return false;
		}

		$userId	= ArrayHelper::getValue($user, 'id', 0, 'int');

		if ($userId)
		{
			try
			{
				$db = JFactory::getDbo();
				$db->setQuery(
					'DELETE FROM #__user_profiles WHERE user_id = ' . $userId .
					" AND profile_key LIKE 'profileYajem.%'"
				);

				if (!$db->execute())
				{
					throw new Exception;
				}
			}
			catch (Exception $e)
			{
				$this->_subject->setError($e->getMessage());

				return false;
			}
		}

		return true;
	}


}