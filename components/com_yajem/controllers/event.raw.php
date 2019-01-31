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
			$view->document = JFactory::getDocument();

			$view->display();
		}
	}
}
