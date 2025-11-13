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

use Exception;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;
use Sda\Component\Sdajem\Administrator\Table\InterestTable;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @since       1.0.0
 * @package     Sda\Component\Sdajem\Administrator\Model
 *
 * Fields
 * @property  int       id
 * @property  int       $access
 * @property  string    alias
 * @property  int       state
 * @property  int       ordering
 * @property  int       event_id
 * @property  int       users_user_id
 * @property  int       status
 * @property  string    comment
 */
class InterestModel extends AdminModel
{
	/**
	 * The type alias for this content type.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	public $typeAlias = 'com_sdajem.interest';

	/**
	 * @param   array  $data
	 * @param   bool   $loadData
	 *
	 * @return Form|false
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		try
		{
			$form = $this->loadForm($this->typeAlias, 'interest', ['control' => 'jform', 'load_data' => $loadData]);
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
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
		$data = $app->getUserState('com_sdajem.edit.interest.data', []);

		if (empty($data)) {
			$data = $this->getItem();
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
	 * @param   InterestTable  $table  The Table object
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