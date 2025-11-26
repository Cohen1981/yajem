<?php

/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Sda\Component\Sdajem\Administrator\Library\Item\Fitting;
use Sda\Component\Sdajem\Administrator\Model\FittingModel;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;

// phpcs:enable PSR1.Files.SideEffects

class FittingformModel extends FittingModel
{
	protected $formName = 'form';

	/**
	 * Method to get attending data.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   integer  $itemId  The id of the attending.
	 *
	 * @return  Fitting  Event item data object on success, false on failure.
	 * @throws  Exception|Exception
	 */
	public function getItem($itemId = null): Fitting
	{
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('fitting.id');
		// Get a row instance.
		$table = $this->getTable();
		// Attempt to load the row.
		try
		{
			if (!$table->load($itemId))
			{
				return new Fitting();
			}
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage());

			return new Fitting();
		}

		return Fitting::createFromObject($table);
		//$properties = $table->getProperties();

		//return ArrayHelper::toObject($properties, stdClass::class);
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
		$this->setState('fitting.id', $pk);
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
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 * @param   string  $name     The table name. Optional.
	 *
	 * @return  Table  A Table object
	 * @throws  Exception
	 */
	public function getTable($name = 'Fitting', $prefix = 'Administrator', $options = [])
	{
		return parent::getTable($name, $prefix, $options);
	}
}
