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

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Document\Document;
use Yajem\Site\Models\YajemModelAttendee;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemControllerEvent extends BaseController
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
		$view = $this->getView('Event', 'html');
		$view->setModel($this->getModel('Event'));
		$view->setModel($this->getModel('Attendees'));
		$view->setModel($this->getModel('Locations'));
		$view->setModel($this->getModel('Comments'));

		$view->display();
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

		if ($todo = $input['register'])
		{
			require_once JPATH_SITE . "/components/com_yajem/models/attendee.php";
			$model = new YajemModelAttendee;

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

		Factory::getApplication()->redirect(JRoute::_('index.php?option=com_yajem&task=event.view&id=' . $input['eventId']), false);
	}

	/**
	 * Method to change the Event status
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0
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

		Factory::getApplication()->redirect(JRoute::_('index.php?option=com_yajem&task=event.view&id=' . $input['eventId']), false);
	}

	/**
	 * @return boolean
	 *
	 * @since version
	 * @throws Exception
	 */
	public function deleteComment()
	{
		$id = Factory::getApplication()->input->get('id');
		require_once JPATH_ADMINISTRATOR . '/components/com_yajem/models/comment.php';

		// JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components/com_yajem/models');
		$modelComment = new YajemModelComment;

		// JModelLegacy::getInstance('Comment', 'YajemModel');
		$modelComment->delete($id);

		return true;
	}

	/**
	 * Non Ajax function
	 * @see See event.raw.php for Ajax function
	 *
	 * @return void
	 * @since version
	 * @throws Exception
	 */
	public function saveComment()
	{
		$input = Factory::getApplication()->input->post->getArray();

		if ($todo = $input['commit_comment'])
		{
			$model = $this->getModel('Comment');
			$model->comment($input['userId'], $input['eventId'], $input['comment']);
		}

		Factory::getApplication()->redirect(JRoute::_('index.php?option=com_yajem&task=event.view&id=' . $input['eventId']), false);
	}
}
