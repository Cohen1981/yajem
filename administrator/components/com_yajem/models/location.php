<?php
/**
 * @package     Yajem.Administrator
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

require_once ("attachments.php");

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemModelLocation extends AdminModel
{
	/**
	 * @var string Just for convenience
	 * @since 1.0
	 */
	protected $textPrefix = 'COM_YAJEM';

	/**
	 * @var null Holds the single location
	 * @since 1.0
	 */
	protected $item = null;

	/**
	 * Method to override getItem to allow us to convert the JSON-encoded image information
	 * in the database record into an array for subsequent prefilling of the edit form
	 *
	 * @param   null $pk The primary key
	 *
	 * @return boolean|JObject
	 *
	 * @since 1.0
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		if ($item && property_exists($item, 'image'))
		{
			$registry = new Registry($item->image);
			$item->params = $registry->toArray();
		}

		return $item;
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $name     The table name to instantiate
	 * @param   string  $prefix   A prefix for the table class name. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return JTable A database object
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function getTable($name = 'Location', $prefix = 'YajemTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return boolean|JForm  A JForm object on success, false on failure
	 *
	 * @since 1.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_yajem.location', 'location',
			array('control' => 'jform',
				'load_data' => $loadData
			)
		);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Loading the form data
	 *
	 * @return array|boolean|JObject|mixed|null
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	protected function loadFormData()
	{
		$data = Factory::getApplication()->getUserState('com_yajem.edit.location.data', array());

		if (empty($data))
		{
			if ($this->item === null)
			{
				$this->item = $this->getItem();
			}

			$data = $this->item;
		}

		return $data;
	}

	/**
	 * @param   JTable $table The Table
	 *
	 * @return void
	 * @since 1.0
	 */
	protected function prepareTable($table)
	{
		if (empty($table->id))
		{
			if (@$table->ordering === '')
			{
				$db = Factory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__yajem_locations');
				$table->ordering = $db->loadResult() + 1;
			}
		}

		parent::prepareTable($table);
	}

	/**
	 * @param array $data
	 *
	 * @return bool
	 *
	 * @since 1.1
	 * @throws Exception
	 */
	public function save($data)
	{
		$return = parent::save($data);

		if ($return)
		{
			$locationId = $this->getState('location.id');
			// getting the list of files to attach
			$jinput          = Factory::getApplication()->input;
			$attachmentsList = $jinput->files->get('jform');

			foreach ($attachmentsList as $attachments)
			{
				$modelAttachments = new YajemModelAttachments();

				$modelAttachments->saveAttachments($attachments, 'location', $locationId);
			}
		}
		return $return;
	}

}
