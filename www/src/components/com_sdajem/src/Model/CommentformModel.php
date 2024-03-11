<?php
/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use Sda\Component\Sdajem\Administrator\Model\CommentModel;

class CommentformModel extends CommentModel
{
	protected $formName = 'form';

	/**
	 * Method to get attending data.
	 *
	 * @param   integer  $itemId  The id of the attending.
	 *
	 * @return  mixed  Event item data object on success, false on failure.
	 *
	 * @throws  Exception|\Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getItem($itemId = null)
	{
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('comment.id');
		// Get a row instance.
		$table = $this->getTable();
		// Attempt to load the row.
		try {
			if (!$table->load($itemId)) {
				return false;
			}
		} catch (Exception $e) {
			Factory::getApplication()->enqueueMessage($e->getMessage());
			return false;
		}
		$properties = $table->getProperties();

		return ArrayHelper::toObject($properties, \stdClass::class);
	}
	/**
	 * Get the return URL.
	 *
	 * @return  string  The return URL.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page'));
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();
		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('comment.id', $pk);
		$return = $app->input->get('return', null, 'base64');
		$this->setState('return_page', base64_decode($return));
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
		$this->setState('layout', $app->input->getString('layout'));
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  Table  A Table object
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws  \Exception
	 */
	public function getTable($name = 'Comment', $prefix = 'Administrator', $options = [])
	{
		return parent::getTable($name, $prefix, $options);
	}
}