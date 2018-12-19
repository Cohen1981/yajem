<?php
/**
 * @version 1.0.0
 * @package Yajem
 * @subpackage Yajem Mailer Plugin
 * @copyright (C) 2018 Alexander Bahlo
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Send Mail at defined events.
 *
 * @package     Yajem
 *
 * @since       1.0
 */
class PlgYajemMailer extends CMSPlugin
{
	private $_SiteName = '';
	private $_MailFrom = '';
	private $_FromName = '';

	/**
	 * Constructor
	 *
	 * @param   object  $subject The object to observe
	 * @param   array   $config  An array that holds the plugin configuration
	 *
	 * @since   1.0
	 * @throws  \Exception
	 */
	public function __construct($subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();

		$app = Factory::getApplication();

		$this->_SiteName     = $app->get('sitename');
		$this->_MailFrom     = $app->get('mailfrom');
		$this->_FromName     = $app->get('fromname');
	}

	/**
	 * @param   int     $eventId
	 * @param   boolean $isNew
	 *
	 * @since   1.0
	 * @throws  \Exception
	 */
	public function onEventEdited($eventId, $isNew)
	{
		$input = Factory::getApplication()->input;
		$data = $input->post->getArray();
		$event = $data['jform'];
		if ( $isNew )
		{
			if ( (bool) $this->params['mail_new_event'] )
			{
				$recipients = $this->getRecipientsMails(false );
				$subject = Text::_('PLG_YAJEM_EVENT_CREATED');

			}
		} else {
			if ( (bool) $this->params['mail_edit_event'] )
			{
				$recipients = $this->getRecipientsMails( (bool) $this->params['mail_event_editAttendees'], $eventId );
				$subject = Text::_('PLG_YAJEM_EVENT_CHANGED');
			}
		}
		$subject = $subject . ": " . $event['title'];
		if ( $event['allDayEvent'] )
		{
			$subject = $subject . " : " . $event['startDate'] . " -> " . $event['endDate'];
		} else {
			$subject = $subject . " : " . $event['startDateTime'] . " -> " . $event['endDateTime'];
		}
		$body = Text::_('PLG_YAJEM_MAIL_BODY');

		$this->sendMail($recipients, $subject, $body);
	}

	/**
	 * @param   int     $eventId
	 * @param   string  $status
	 *
	 *
	 * @since   1.0
	 * @throws  \Exception
	 */
	public function onChangeEventState($eventId, $status)
	{
		if ( (bool) $this->params['mail_event_state'] )
		{
			//load model for recieving additional event information
			JModelLegacy::addIncludePath(JPATH_SITE . DS . 'components' . DS . 'com_yajem' . DS . 'models');

			//get the Event
			$modelEvent = JModelLegacy::getInstance('event', 'YajemModel');
			$event = $modelEvent->getData($eventId);

			//get the location
			$modelLocation = JModelLegacy::getInstance('locations', 'YajemModel');
			$location = $modelLocation->getLocation( (int) $event->locationId );

			//get the recipients
			$recipients = $this->getRecipientsMails( (bool) $this->params['mail_event_stateAttendees'], $eventId );
			//build the subject
			if ( $status == 'confirmed' )
			{
				$subject = Text::_('PLG_YAJEM_EVENT_STATE_CONFIRMED');
			} else {
				$subject = Text::_('PLG_YAJEM_EVENT_STATE_CANCELLED');
			}
			$subject = $subject . " => " . $event->title;
			if ( (bool) $event->allDayEvent )
			{
				$body = Text::_('PLG_YAJEM_EVENT_TIME') . " : " . $event->startDate . " -> " . $event->endDate;
			} else {
				$body = Text::_('PLG_YAJEM_EVENT_TIME') . " : " . $event->startDateTime . " -> " . $event->endDateTime;
			}
			$body = $this->addLocationToBody($location, $body);

			$this->sendMail($recipients, $subject, $body);
		}
	}

	/**
	 *
	 * @param boolean   $attendees  true if only event attendees should be used
	 * @param int       eventId     id for the event -> default null if new event
	 *
	 * @return array    array holding all recipients email
	 *
	 * @since 1.0
	 */
	private function getRecipientsMails($attendees, $eventId = null)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('u.email');
		$query->from('#__users AS u');
		if ($attendees)
		{
			//select attendees
			$query->innerJoin( '#__yajem_attendees AS a ON a.userId = u.id' );
			$query->where('a.eventId = ' . (int) $eventId );
		} else {
			if ( !(bool) $this->params['mail_to_all'])
			{
				$query->innerJoin( '#__user_usergroup_map AS ug ON ug.user_id = u.id' );
				$query->where( 'ug.group_id = ' . (int) $this->params['mail_to_group'] );
			}
		}
		$db->setQuery($query);
		return $db->loadColumn();
	}

	/**
	 * @param   array       $recipients
	 * @param   string      $subject
	 * @param   string      $body
	 *
	 * @since   1.0
	 * @throws  Exception
	 */
	private function sendMail($recipients, $subject, $body)
	{
		if ( !empty($recipients) && !empty($subject) && !empty($body) )
		{
			$mailer = Factory::getMailer();
			$mailer->setSender(array($this->_MailFrom, $this->_FromName));
			$mailer->setSubject($subject);
			$mailer->setBody($body);
			$mailer->addBcc($recipients);
			$send = $mailer->Send();
			if ($send !== true)
			{
				Factory::getApplication()->enqueueMessage(Text::_('PLG_YAJEM_MAIL_SEND_ERROR'), 'error');
			}
			else
			{
				Factory::getApplication()->enqueueMessage(Text::_('PLG_YAJEM_MAIL_SEND_SUCCESS'), 'message');
			}
		}
	}

	/**
	 * @param JModelLegacy    $location
	 * @param string $body
	 *
	 * @return string
	 *
	 * @since 1.0
	 */
	private function addLocationToBody($location, $body = "")
	{
		$body = $body . " \n\n " . Text::_('PLG_YAJEM_EVENT_LOCATION') . "\n" .
			$location->title . "\n" .
			$location->street . "\n" .
			$location->postalCode . " " . $location->city;
		return $body;
	}

}