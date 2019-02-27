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
use Yajem\Models\EquipmentItem;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemModelEvent extends AdminModel
{
	/**
	 * @var string Just for convenience
	 * @since 1.0
	 */
	protected $textPrefix = 'COM_YAJEM';

	/**
	 * @var EquipmentItem|null Holds the single equipmentItem
	 * @since 1.0
	 */
	protected $item = null;

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $name     The table name to instantiate
	 * @param   string  $prefix   A prefix for the table class name. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return \JTable A database object
	 *
	 * @since 1.0
	 * @throws \Exception
	 */
	public function getTable($name = 'Equipment', $prefix = 'YajemTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return boolean|\JForm  A JForm object on success, false on failure
	 *
	 * @since 1.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_yajem.event', 'event',
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
	 * @return array|boolean|\JObject|mixed|null
	 *
	 * @since 1.0
	 * @throws \Exception
	 */
	protected function loadFormData()
	{
		$data = Factory::getApplication()->getUserState('com_yajem.edit.event.data', array());

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
	 * @param   \YajemTableEvent $table The Table
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
				$db->setQuery('SELECT MAX(ordering) FROM #__yajem_events');
				$table->ordering = $db->loadResult() + 1;
			}
		}

		parent::prepareTable($table);
	}

	/**
	 * @param   array $data The event data
	 *
	 * @return boolean
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function save($data)
	{
		$saved = parent::save($data);

		if ($saved)
		{
			$eventId = ($this->getState('event.id') != null ? $this->getState('event.id') : $this->getState('editevent.id'));

			if ($data['invited_users'])
			{
				foreach ($data['invited_users'] as $user)
				{
					$modelAttendee = new YajemModelAttendee;
					$invited = array('eventId' => $eventId, 'userId' => $user, 'status' => 0, 'comment' => '');
					$modelAttendee->save($invited);
				}
			}

			// Getting the list of files to attach
			$jinput = Factory::getApplication()->input;
			$attachmentsList = $jinput->files->get('jform');

			foreach ($attachmentsList as $attachments)
			{
				$modelAttachments = new YajemModelAttachments;

				$modelAttachments->saveAttachments($attachments, 'event', $eventId);
			}
		}

		return $saved;
	}
}
