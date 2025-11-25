<?php


/**
 * Copyright (c) 2025. Alexander Bahlo <abahlo@hotmail.de
 */

namespace Sda\Component\Sdajem\Site\Model;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use Sda\Component\Sdajem\Administrator\Library\Item\Comment;
use Sda\Component\Sdajem\Administrator\Model\CommentModel;
use stdClass;

/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @since       1.5.1
 */
class CommentformModel extends CommentModel
{
	/**
	 * @var string
	 * @since 1.5.1
	 */
	protected $formName = 'form';

	/**
	 * Method to get attending data.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   integer  $itemId  The id of the attending.
	 *
	 * @return  mixed  Event item data object on success, false on failure.
	 * @throws  Exception|Exception
	 */
	public function getItem($itemId = null)
	{
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('comment.id');
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
		$this->setState('comment.id', $pk);
		$return = $app->input->get('return', '', 'base64');
		$this->setState('return_page', base64_decode($return));
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
		$this->setState('layout', $app->input->getString('layout'));
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
	public function getTable($name = 'Comment', $prefix = 'Administrator', $options = [])
	{
		return parent::getTable($name, $prefix, $options);
	}

	protected function loadFormData()
	{
		$app = Factory::getApplication();

		/** @var Comment $data */
		// Check the session for previously entered form data.
		$data = $app->getUserState('com_sdajem.edit.comment.data', []);

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$data->sdajem_event_id = $app->getUserState('com_sdajem.comment.sdajem_event_id', 0);
		$data->users_user_id   = $app->getIdentity()->id;

		$this->preprocessData($this->typeAlias, $data);

		return $data;
	}
}
