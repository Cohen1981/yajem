<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * @since       __BUMP_VERSION__
 * @package     Sda\Component\Sdajem\Administrator\Model
 *
 * @property  int       id
 * @property  string    title
 * @property  string    alias
 * @property  string    description
 * @property  string    url
 */
class EventModel extends AdminModel
{
	public $typeAlias = 'com_sdajem.event';
	/**
	 * @param   array  $data
	 * @param   bool   $loadData
	 *
	 * @return Form|false
	 *
	 * @since __BUMP_VERSION__
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		try
		{
			$form = $this->loadForm($this->typeAlias, 'event', ['control' => 'jform', 'load_data' => $loadData]);
		}
		catch (\Exception $e)
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
	 * @throws \Exception
	 * @since   __BUMP_VERSION__
	 */
	protected function loadFormData()
	{
		$app = Factory::getApplication();
		$data = $this->getItem();
		$this->preprocessData($this->typeAlias, $data);
		return $data;
	}
	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   \Sda\Component\Sdajem\Administrator\Table\EventTable  $table  The Table object
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function prepareTable($table)
	{
		$table->generateAlias();
	}

	protected function populateState()
	{
		$app = Factory::getApplication();
		$this->setState('event.id', $app->input->getInt('id'));
		$this->setState('params', $app->getParams());
	}
}