<?php
/**
 * Copyright (c) 2025. Alexander Bahlo <abahlo@hotmail.de
 */

namespace Sda\Component\Sdajem\Site\Model;

defined('_JEXEC') or die();

use Exception;
use JForm;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use stdClass;

class AttendingformModel extends \Sda\Component\Sdajem\Administrator\Model\AttendingModel
{
	/**
	 * Model typeAlias string. Used for version history.
	 *
	 * @since  __DEPLOY_VERSION__
	 * @var  string
	 */
	public $typeAlias = 'com_sdajem.attending';
	/**
	 * Name of the form
	 *
	 * @since  __DEPLOY_VERSION__
	 * @var string
	 */
	protected $formName = 'form';

	/**
	 * Method to get the row form.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 * @param   array    $data      Data for the form.
	 *
	 * @return  JForm|boolean  A \JForm object on success, false on failure
	 */
	public function getForm($data = [], $loadData = true)
	{
		$form = parent::getForm($data, $loadData);

		return $form;
	}

	/**
	 * Method to get attending data.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   null  $pk
	 *
	 * @return  mixed  Event item data object on success, false on failure.
	 * @throws Exception
	 */
	public function getItem($pk = null): mixed
	{
		$itemId = (int) (!empty($pk)) ? $pk : $this->getState('attending.id');
		// Get a row instance.
		$table = $this->getTable();
		// Attempt to load the row.
		try
		{
			if (!$table->load($itemId))
			{
				return false;
			}
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage());

			return false;
		}
		$properties = $table->getProperties();

		return ArrayHelper::toObject($properties, stdClass::class);
	}

	/**
	 * Get the return URL.
	 *
	 * @since   __DEPLOY_VERSION__
	 * @return  string  The return URL.
	 */
	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page'));
	}

	/**
	 * Method to save the form data.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 * @throws Exception
	 */
	public function save($data)
	{
		return parent::save($data);
	}

	/**
	 * Method to auto-populate the model state.
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   __DEPLOY_VERSION__
	 * @return  void
	 * @throws  Exception
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();
		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('attending.id', $pk);
		$return = $app->input->get('return', null, 'base64');
		$this->setState('return_page', base64_decode($return));
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
		$this->setState('layout', $app->input->getString('layout'));
	}

	/**
	 * Allows preprocessing of the JForm object.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   array   $data   The data to be merged into the form object
	 * @param   string  $group  The plugin group to be executed
	 * @param   Form    $form   The form object
	 *
	 * @return void
	 * @throws Exception
	 */
	protected function preprocessForm(Form $form, $data, $group = 'attending'): void
	{
		if (!Multilanguage::isEnabled())
		{
			$form->setFieldAttribute('language', 'type', 'hidden');
			$form->setFieldAttribute('language', 'default', '*');
		}
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 * @param   string  $name     The table name. Optional.
	 *
	 * @return  Table  A Table object
	 * @throws  Exception
	 */
	public function getTable($name = 'Attending', $prefix = 'Administrator', $options = [])
	{
		return parent::getTable($name, $prefix, $options);
	}
}
