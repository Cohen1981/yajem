<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

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
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use RuntimeException;
use Sda\Component\Sdajem\Site\Model\Item\Event;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

class EventformModel extends \Sda\Component\Sdajem\Administrator\Model\EventModel
{
	/**
	 * Model typeAlias string. Used for version history.
	 *
	 * @var  string
	 * @since  __DEPLOY_VERSION__
	 */
	public $typeAlias = 'com_sdajem.event';
	/**
	 * Name of the form
	 *
	 * @var string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $formName = 'form';

	/**
	 * Method to get event data.
	 *
	 * @param   integer  $itemId  The id of the event.
	 *
	 * @return  mixed  Event item data object on success, false on failure.
	 *
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getItem($itemId = null):Event
	{
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('event.id');
		// Get a row instance.
		$table = $this->getTable();
		// Attempt to load the row.
		try {
			if (!$table->load($itemId)) {
				return false;
			}
			else{
				if($table->svg)
					$table->svg = (array) json_decode($table->svg);
				else
					$table->svg = array();
			}
		} catch (Exception $e) {
			Factory::getApplication()->enqueueMessage($e->getMessage());
			return false;
		}
		 return Event::createFromObject($table);
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
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws Exception
	 * @since   __DEPLOY_VERSION__
	 */
	public function save($data)
	{
		if (is_array($data['svg']))
			$data['svg'] = json_encode($data['svg']);
		return parent::save($data);
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
		$this->setState('event.id', $pk);
		$this->setState('event.catid', $app->input->getInt('catid'));
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
	 * @param   Form    $form   The form object
	 * @param   array   $data   The data to be merged into the form object
	 * @param   string  $group  The plugin group to be executed
	 *
	 * @return Form|void
	 *
	 * @throws Exception
	 * @since   __DEPLOY_VERSION__
	 */
	protected function preprocessForm(Form $form, $data, $group = 'event')
	{
		if (!Multilanguage::isEnabled()) {
			$form->setFieldAttribute('language', 'type', 'hidden');
			$form->setFieldAttribute('language', 'default', '*');
		}
		parent::preprocessForm($form, $data, $group);
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
	 * @throws  Exception
	 */
	public function getTable($name = 'Event', $prefix = 'Administrator', $options = [])
	{
		return parent::getTable($name, $prefix, $options);
	}

	public function updateEventStatus(int $pk = null, EventStatusEnum $enum)
	{
		if ($pk != null) {
			// Initialize variables.
			$db    = $this->getDatabase();
			$query = $db->getQuery(true);

			$query->update($db->quoteName('#__sdajem_events'));
			$query->set($db->quoteName('eventStatus') . '=' . $enum->value);
			$query->where($db->quoteName('id') . '=' . $pk);

			$db->setQuery($query);
			try {
				$db->execute();
			} catch (RuntimeException $e) {
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');

				return false;
			}

			$this->cleanCache();

			return true;
		} else
		{
			Factory::getApplication()->enqueueMessage(Text::sprintf('COM_SDAJEM_MISSING_ID'), 'error');

			return false;
		}
	}

	public function updateEventAccess(int $pk = null, int $access)
	{
		if ($pk != null) {
			// Initialize variables.
			$db    = $this->getDatabase();
			$query = $db->getQuery(true);

			$query->update($db->quoteName('#__sdajem_events'));
			$query->set($db->quoteName('access') . '=' . $access);
			$query->where($db->quoteName('id') . '=' . $pk);

			$db->setQuery($query);
			try {
				$db->execute();
			} catch (RuntimeException $e) {
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');

				return false;
			}

			$this->cleanCache();

			return true;
		} else
		{
			Factory::getApplication()->enqueueMessage(Text::sprintf('COM_SDAJEM_MISSING_ID'), 'error');

			return false;
		}
	}
}