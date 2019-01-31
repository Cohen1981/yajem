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
use Joomla\Component\Yajem\Administrator\Helpers\YajemParams;
use Joomla\Component\Yajem\Administrator\Helpers\YajemHtmlHelper;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemViewEvent extends JViewLegacy
{
	/**
	 * @var state
	 * @since 1.0
	 */
	protected $state;

	/**
	 * @var event
	 * @since 1.0
	 */
	protected $event;

	/**
	 * @param   null $tpl Template to load
	 *
	 * @return mixed
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$input = Factory::getApplication()->input->post->getArray();

		if (count($input))
		{
			switch ($input['task'])
			{
				case 'event.saveComment':
					$this->afterComment($input);
					break;
				case 'event.getIcs':
					$this->returnIcs();
					break;
				case 'event.changeEventStatus':
					$this->changeEventStatus($input);
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Returns the ical file
	 *
	 * @return void
	 * @since version
	 * @throws Exception
	 */
	private function returnIcs()
	{
		$this->state = $this->get('State');

		$ics = $this->getModel('Event')->makeIcs();

		$document = JFactory::getDocument();
		$document->setMimeEncoding('text/calendar; charset=utf-8');
		JFactory::getApplication()
			->setHeader(
				'Content-disposition',
				'attachment; filename="invite.ics"; creation-date="' . JFactory::getDate()->toRFC822() . '"',
				true
			)
			->setHeader('Content-Type', 'text/calendar; charset=utf-8', true)
			->setHeader('Content-Length', strlen($ics), true)
			->setHeader('Connection', 'close', true);

		echo $ics;
	}

	/**
	 * @param   array $input The input of the call
	 *
	 * @return void
	 * @since version
	 * @throws Exception
	 */
	private function afterComment(array $input)
	{
		$document = JFactory::getDocument();
		$document->setMimeEncoding('text/html; charset=utf-8');
		Factory::getApplication()->setHeader('Content-Type', 'text/html; charset=utf-8', true);

		$commentId = $this->getModel('Comment')->getState('comment.id');
		$timestamp = $this->getModel('Comment')->getState('timestampSave');
		$userProfile = YajemUserHelper::getUser($input['userId']);
		$return = $this->getCommentHtml($commentId, $userProfile, $timestamp, $input['comment']);
		echo $return;
	}

	/**
	 * @param   int         $commentId      The new Comment ID
	 * @param   array       $userProfile    The userProfile
	 * @param   string      $timestamp      Timestamp the comment was created
	 * @param   string      $comment        The comment
	 *
	 * @return string
	 *
	 * @since version
	 */
	private function getCommentHtml($commentId, $userProfile, $timestamp, $comment)
	{
		$html = "";

		include_once JPATH_ADMINISTRATOR . "/components/com_yajem/helpers/YajemParams.php";
		$yajemParams = new YajemParams;

		if ($yajemParams->useUserProfile)
		{
			// Which Avatar to use
			if ($userProfile['avatar'])
			{
				$avatar = '<img id="avatar_' . $commentId . '" class="yajem_comment_avatar yajem_img_round" src="' .
					$userProfile['avatar'] . '"/>';
			}
			else
			{
				$avatar = '<img id="avatar_' . $commentId . '" class="yajem_comment_avatar" src="' .
					JURI::root() . '/media/com_yajem/images/user-image-blanco.png"/>';
			}

			$html = $avatar;
		}

		$html = $html . "<div id=\"output_" . $commentId . "\" class=\"yajem_comment_output\">";
		$html = $html . "<div class=\"yajem_uname\">";
		$html = $html . "<div class=\"yajem_pull_right\">";
		$html = $html . "<a onclick=\"delComment(" . $commentId . ")\">";
		$html = $html . "<i class=\"fas fa-trash-alt\" aria-hidden=\"true\"></i>";
		$html = $html . "</a></div>";
		$html = $html . $userProfile['name'] . " " . $timestamp;
		$html = $html . "</div><div class=\"yajem_comment\">" . nl2br($comment) . "</div></div>";

		return $html;
	}

	/**
	 *
	 * @param   array $input The Request input
	 *
	 * @return void
	 *
	 * @throws Exception
	 * @since version
	 */
	private function changeEventStatus($input)
	{
		$html = "";
		$document = JFactory::getDocument();
		$document->setMimeEncoding('text/html; charset=utf-8');
		Factory::getApplication()->setHeader('Content-Type', 'text/html; charset=utf-8', true);
		include_once JPATH_SITE . "/administrator/components/com_yajem/helpers/YajemHtmlHelper.php";
		$helper = new YajemHtmlHelper;

		if ($input['eStatus'] == 'confirm')
		{
			$html = $helper->links->cancel;
		}
		else
		{
			$html = $helper->links->confirm;
		}

		echo $html;
	}
}