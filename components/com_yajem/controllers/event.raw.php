<?php
/**
 * @package     Yajem.Site
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Component\Yajem\Administrator\Classes\YajemUserProfile;
use Yajem\Site\Models\YajemModelAttendee;

jimport('joomla.application.component.model');

/**
 * @package     Yajem
 *
 * @since       1.1
 */
class YajemControllerEvent extends JControllerLegacy
{
	/**
	 *
	 * @return JControllerLegacy|void
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function view()
	{
		$view = $this->getView('Event', 'raw');
		$view->setModel($this->getModel('Event'));
		$view->setModel($this->getModel('Attendees'));
		$view->setModel($this->getModel('Locations'));

		$view->display();
	}

	/**
	 *
	 * @return void
	 * @since 1.2.0
	 * @throws Exception
	 */
	public function changeEventStatus()
	{
		$input = Factory::getApplication()->input->post->getArray();

		if ($todo = $input['eStatus'])
		{
			PluginHelper::importPlugin('yajem');
			$model = $this->getModel('Event');

			switch ($todo)
			{
				case 'confirm':
					$model->changeStatus($input['eventId'], 1);
					Factory::getApplication()->triggerEvent('onChangeEventState', array($input['eventId'], 'confirmed'));
					break;
				case 'cancel':
					$model->changeStatus($input['eventId'], 2);
					Factory::getApplication()->triggerEvent('onChangeEventState', array($input['eventId'], 'cancelled'));
					break;
			}
		}

		if ($view = $this->getView('Event', 'raw'))
		{
			$view->setModel($model);
			$view->document = JFactory::getDocument();

			$view->display();
		}
	}

	/**
	 *
	 * @return void
	 * @since 1.1.0
	 * @throws Exception
	 */
	public function getIcs()
	{
		if ($view = $this->getView('Event', 'raw'))
		{
			$view->setModel($this->getModel('Event'));
			$view->setModel($this->getModel('Locations'));

			$view->document = JFactory::getDocument();

			$view->display();
		}
	}

	/**
	 * Function is for Ajax commenting.
	 *
	 * @return void
	 * @since 1.2.0
	 * @throws Exception
	 */
	public function saveComment()
	{
		$input = Factory::getApplication()->input->post->getArray();

		if ($input['userId'] && $input['eventId'] && $input['comment'])
		{
			$timestamp = Factory::getDate()->toSql();
			$data['id'] = null;
			$data['userId'] = $input['userId'];
			$data['eventId'] = $input['eventId'];
			$data['comment'] = $input['comment'];
			$data['timestamp'] = $timestamp;

			require_once JPATH_ADMINISTRATOR . '/components/com_yajem/models/comment.php';
			$model = new YajemModelComment;
			$model->setState('timestampSave', $timestamp);
			$model->save($data);
		}

		if ($view = $this->getView('Event', 'raw'))
		{
			$view->setModel($model);
			$eventModel = $this->getModel('Event', 'YajemModel');
			$eventModel->setState('item.id', $input['eventId']);
			$view->setModel($eventModel);
			$view->document = JFactory::getDocument();

			$view->display();
		}
	}

	/**
	 * Method to change the attending status of a user for one event
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	public function changeAttendingStatus()
	{
		$input = Factory::getApplication()->input->post->getArray();

		if ($input['register'] && $input['eventId'])
		{
			require_once JPATH_ROOT . "/components/com_yajem/models/attendee.php";
			$model = new YajemModelAttendee;
			$model->set('name', 'attendee');
			$todo = $input['register'];

			switch ($todo)
			{
				case 'reg':
					$model->registration($input['id'], $input['eventId'], 1);
					break;
				case 'regw':
					$model->registration($input['id'], $input['eventId'], 3);
					break;
				case 'unreg':
					$model->registration($input['id'], $input['eventId'], 2);
					break;
			}
		}

		if ($view = $this->getView('Event', 'raw'))
		{
			$eventModel = $this->getModel('Event', 'YajemModel');
			$eventModel->setState('item.id', $input['eventId']);
			$view->setModel($eventModel);
			$view->setModel($model);
			$view->document = JFactory::getDocument();

			$view->display();
		}
	}

	/**
	 *
	 * @return void
	 * @throws Exception
	 * @since version
	 */
	public function getRegButtons()
	{
		$input = Factory::getApplication()->input->post->getArray();

		if ($view = $this->getView('Event', 'raw'))
		{
			$eventModel = $this->getModel('Event', 'YajemModel');
			$eventModel->setState('item.id', $input['eventId']);
			$view->setModel($eventModel);
			$view->document = JFactory::getDocument();

			$view->display();
		}
	}
}
