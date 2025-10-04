<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Controller;

\defined('_JEXEC') or die();

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Input\Input;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Sda\Component\Sdajem\Administrator\Model\AttendingModel;
use Sda\Component\Sdajem\Administrator\Model\AttendingsModel;
use Sda\Component\Sdajem\Administrator\Model\EventModel;

class EventsController extends AdminController
{
	/**
	 * Constructor.
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 * @param   MVCFactoryInterface  $factory  The factory.
	 * @param   CMSApplication       $app      The JApplication for the dispatcher
	 * @param   Input                $input    Input
	 *
	 * @since   1.0.0
	 */
	public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		parent::__construct($config, $factory, $app, $input);
	}
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The name of the model.
	 * @param   string  $prefix  The prefix for the PHP class name.
	 * @param   array   $config  Array of configuration parameters.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel
	 *
	 * @since   1.0.0
	 */
	public function getModel($name = 'Event', $prefix = 'Administrator', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 *
	 * @return bool
	 *
	 * @since 1.0.8
	 */
	public function delete()
	{
		$pks = $this->input->get('cid');

		$attendingFormModel = new AttendingModel();
		$attendingsModel = new AttendingsModel();

		foreach ($pks as &$pk) {
			$attendings = $attendingsModel->getAttendingsIdToEvent($pk);
			$attResult = $attendingFormModel->delete($attendings);
		}

		$eventFormModel = new EventModel();

		if ($attResult)
		{
			$result = $eventFormModel->delete($pks);
		}

		$this->setRedirect(
			Route::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_list
				. $this->getRedirectToListAppend(),
				false
			)
		);
		return $result;
	}
}