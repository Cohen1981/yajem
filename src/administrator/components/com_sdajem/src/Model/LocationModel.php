<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @package     Sda\Component\Sdajem\Administrator\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Model;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;
use Sda\Component\Sdajem\Administrator\Model\Item\LocationItem;
use Sda\Component\Sdajem\Administrator\Table\LocationTable;

/**
 * @since       1.0.0
 * @package     Sda\Component\Sdajem\Administrator\Model
 */
class LocationModel extends AdminModel
{
	/**
	 * The type alias for this content type.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	public $typeAlias = 'com_sdajem.location';

	/**
	 * @param   array  $data
	 * @param   bool   $loadData
	 *
	 * @return Form|false
	 *
	 * @since 1.0.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		try
		{
			$form = $this->loadForm($this->typeAlias, 'location', ['control' => 'jform', 'load_data' => $loadData]);
		}
		catch (Exception $e)
		{
			return false;
		}
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @throws Exception
	 * @since   1.0.0
	 */
	protected function loadFormData()
	{
		$app = Factory::getApplication();

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_sdajem.edit.location.data', []);

		if (empty($data)) {
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('location.id') == 0) {
				$data->catid = $app->input->get('catid', $app->getUserState('com_sdajem.locations.filter.category_id'), 'int');
			}
		}

		$this->preprocessData($this->typeAlias, $data);

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   1.0.0
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   LocationTable  $table  The Table object
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function prepareTable($table)
	{
		$table->generateAlias();
	}
}