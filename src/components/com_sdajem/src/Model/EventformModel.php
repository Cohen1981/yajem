<?php

/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage  Sda
 * @copyright   2025 GNU General Public License version 2 or later;
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use RuntimeException;
use Sda\Component\Sdajem\Administrator\Library\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Administrator\Library\Item\Event;

use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @since       1.0.0
 */
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
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   int  $pk  The id of the event.
	 *
	 * @return  mixed  Event item data object on success, false on failure.
	 * @throws  Exception
	 */
	public function getItem($pk = null): Event
	{
		$pk = (int) (!empty($pk)) ? $pk : $this->getState('event.id');

		// Get a row instance.
		$table = $this->getTable();

		// Attempt to load the row.
		try
		{
			if (!$table->load($pk))
			{
				return false;
			}
			elseif ($table->svg)
			{
				$table->svg = (array) json_decode($table->svg);
			}
			else
			{
				$table->svg = array();
			}
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage());

			return false;
		}

		return Event::createFromObject($table);
	}

	/**
	 * Get the return URL.
	 *
	 * @since   __DEPLOY_VERSION__
	 * @return  string  The return URL.
	 */
	public function getReturnPage(): string
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
	 * @return  bool  True on success.
	 * @throws Exception
	 */
	public function save($data): bool
	{
		if (is_array($data['svg']))
		{
			$data['svg'] = json_encode($data['svg']);
		}

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
	protected function populateState(): void
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
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   array   $data   The data to be merged into the form object
	 * @param   string  $group  The plugin group to be executed
	 * @param   Form    $form   The form object
	 *
	 * @return Form|void
	 * @throws Exception
	 */
	protected function preprocessForm(Form $form, $data, $group = 'event')
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
	public function getTable($name = 'Event', $prefix = 'Administrator', $options = [])
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * @since version
	 *
	 * @param   EventStatusEnum  $enum  The new status of the event
	 * @param   int|null         $pk    The primary key of the event
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function updateEventStatus(int $pk = null, EventStatusEnum $enum): bool
	{
		if ($pk != null)
		{
			// Initialize variables.
			$db    = $this->getDatabase();
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__sdajem_events'));
			$query->set($db->quoteName('eventStatus') . '=' . $enum->value);
			$query->where($db->quoteName('id') . '=' . $pk);
			$db->setQuery($query);

			try
			{
				$db->execute();
			}
			catch (RuntimeException $e)
			{
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');

				return false;
			}

			$this->cleanCache();

			return true;
		}
		else
		{
			Factory::getApplication()->enqueueMessage(Text::sprintf('COM_SDAJEM_MISSING_ID'), 'error');

			return false;
		}
	}

	/**
	 * @since version
	 *
	 * @param   int       $access  new access
	 * @param   int|null  $pk      pk
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function updateEventAccess(int $pk = null, int $access): bool
	{
		if ($pk != null)
		{
			// Initialize variables.
			$db    = $this->getDatabase();
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__sdajem_events'));
			$query->set($db->quoteName('access') . '=' . $access);
			$query->where($db->quoteName('id') . '=' . $pk);
			$db->setQuery($query);

			try
			{
				$db->execute();
			}
			catch (RuntimeException $e)
			{
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');

				return false;
			}

			$this->cleanCache();

			return true;
		}
		else
		{
			Factory::getApplication()->enqueueMessage(Text::sprintf('COM_SDAJEM_MISSING_ID'), 'error');

			return false;
		}
	}
}
