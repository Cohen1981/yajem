<?php
/**
 * @package     Sda\Jem\Site\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Site\Controller;

use FOF30\Controller\DataController;
use FOF30\Date\Date;
use Joomla\CMS\Factory;
use FOF30\Container\Container;

/**
 * @package     Sda\Jem\Site\Controller
 *
 * @since       0.0.1
 */
class Comment extends DataController
{
	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function addCommentAjax()
	{
		$input = $this->input->request->getArray();

		if ($input['eventId'] && $input['comment'])
		{
			$userId = Factory::getUser()->id;
			/** @var \Sda\Jem\Site\Model\Comment $comment */
			$comment = $this->getModel();

			$comment->sdajem_event_id   = $input['eventId'];
			$comment->comment           = $input['comment'];
			$comment->timestamp         = new Date($this->input->server->getString('REQUEST_TIME'));
			$comment->users_user_id     = $userId;
			$comment->commentReadBy = array($userId);

			$comment->save();
			$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Comment&task=commentAjax&id=' . $comment->sdajem_comment_id);
		}
		else
		{
			$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Comment&task=error');
		}

		$this->redirect;
	}

	/**
	 * Deletes a Comment
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function deleteCommentAjax()
	{
		$input = $this->input->post->getArray();

		if ($input['id'])
		{
			/** @var \Sda\Jem\Site\Model\Comment $comment */
			$comment = Container::getInstance('com_sdajem')->factory->model('comment');
			$comment->forceDelete($input['id']);
		}
		else
		{
			$this->setRedirect('index.php?option=com_sdajem&format=raw&view=Comment&task=error');
		}

		$this->redirect;
	}

}
