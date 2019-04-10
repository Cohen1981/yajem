<?php
/**
 * @version 1.0.0
 * @package SDA
 * @subpackage Sda Mailer Plugin
 * @copyright (C) 2018 Alexander Bahlo
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Sda\Jem\Admin\Model\Event;
use FOF30\Container\Container;
use Joomla\CMS\Component\ComponentHelper;
use Sda\Jem\Admin\Model\Mailing;
use Sda\Jem\Admin\Model\User;
use Sda\Profiles\Admin\Model\Profile as ProfileAlias;

/**
 * Send Mail at defined events.
 *
 * @package     Sda
 *
 * @since       1.0
 */
class plgSystemSdamailer extends CMSPlugin
{
	/**
	 * @var mixed|string
	 * @since version
	 */
	private $siteName = '';

	/**
	 * @var mixed|string
	 * @since version
	 */
	private $mailFrom = '';

	/**
	 * @var mixed|string
	 * @since version
	 */
	private $fromName = '';
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
		$this->siteName = $app->get('sitename');
		$this->mailFrom = $app->get('mailfrom');
		$this->fromName = $app->get('fromname');

		if (!defined('FOF30_INCLUDED') && !@include_once JPATH_LIBRARIES . '/fof30/include.php')
		{
			throw new RuntimeException('FOF 3.0 is not installed', 500);
		}
	}

	/**
	 * @param   Event $event The Event
	 *
	 * @return boolean
	 *
	 * @throws Exception
	 * @since   1.0
	 */
	public function onComSdajemModelEventAfterSave(Event $event)
	{
		$input = $array = Factory::getApplication()->input->post->getArray();

		if ($input['task'] == 'save' || $input['task'] == 'apply')
		{
			$recipients = $array;
			$subject = "";

			if ($input['sdajem_event_id'] == "")
			{
				$isNew = true;
			}
			else
			{
				$isNew = false;
			}

			// Check for new or edtited event
			if ($isNew)
			{
				// Mailing enabled for new event ?
				if ((bool) $this->params['mail_on_new'])
				{
					$recipients = $this->getRecipientsMails($event, $isNew);
					$subject = Text::_('PLG_SYSTEM_SDAMAILER_EVENT_CREATED');
				}
			}
			else
			{
				// Mailing enabled for edited event ?
				if ((bool) $this->params['mail_on_edited'])
				{
					$recipients = $this->getRecipientsMails($event, $isNew);
					$subject = Text::_('PLG_SYSTEM_SDAMAILER_EVENT_CHANGED');
				}
			}

			$subject = $subject . ": " . $event->title;
			$subject = $subject . " : " . $event->getFormatedStartDate() . " -> " . $event->getFormatedEndDate();

			$body = "";

			if (!empty($event->description))
			{
				$body = Text::_('PLG_SYSTEM_SDAMAILER_EVENT_DESC') . "\n" . $event->description . "\n\n";
			}

			if ($event->organizer)
			{
				$body = $body . Text::_('PLG_SYSTEM_SDAMAILER_EVENT_ORGANIZER') . "\n" . $event->organizer->name . "\n\n";
			}

			if ($event->registerUntil)
			{
				$body = $body . Text::_('PLG_SYSTEM_SDAMAILER_EVENT_REGUNTIL') . "\n" . $event->registerUntil->format('d.m.Y') . "\n\n";
			}

			if ($event->location)
			{
				$body = $body . Text::_('PLG_SYSTEM_SDAMAILER_EVENT_LOCATION') . "\n" .
					$event->location->title . "\n" .
					$event->location->street . "\n" .
					$event->location->postalCode . " " . $event->location->city;
			}

			$this->sendMail($recipients, $subject, $body);
		}

		return true;
	}

	/**
	 *
	 * @param   Event $event The Event object
	 * @param   bool  $isNew New Event. If false is edited
	 *
	 * @return array    array holding all recipients email
	 *
	 * @throws Exception
	 * @since 1.0
	 */
	private function getRecipientsMails(Event $event, bool $isNew)
	{
		/** @var array $recipients */
		$recipients = array();

		if ($isNew)
		{
			/*
			 * New event
			 */

			// Is com_sdaprofiles active
			if (ComponentHelper::isEnabled('com_sdaprofiles'))
			{
				/** @var ProfileAlias $profileModel */
				$profileModel = Container::getInstance('com_sdaprofiles')->factory->model('Profile');
				/** @var array $profileArray */
				$profileArray = $profileModel->getItemsArray(0, 0, true);

				/** @var ProfileAlias $profile */
				foreach ($profileArray as $profile)
				{
					// Each User can decide if he wants to have an email on new Events
					if ((bool) $profile->mailOnNew)
					{
						array_push($recipients, $profile->user->email);
					}
				}
			}
			else
			{
				/** @var User $userModel */
				$userModel = Container::getInstance('com_sdajem')->factory->model('User');
				/** @var array $userArray */
				$userArray = $userModel->getItemsArray();

				/** @var User $user */
				foreach ($userArray as $user)
				{
					array_push($recipients, $user->email);
				}
			}
		}
		else
		{
			/*
			 * Edited event
			 */

			// Is com_sdaprofiles active
			if (ComponentHelper::isEnabled('com_sdaprofiles'))
			{
				/** @var ProfileAlias $profileModel */
				$profileModel = Container::getInstance('com_sdaprofiles')->factory->model('Profile');
				/** @var array $profileArray */
				$profileArray = $profileModel->getItemsArray(0, 0, true);

				/** @var ProfileAlias $profile */
				foreach ($profileArray as $profile)
				{
					// Each User can decide if he wants to have an email on edited Events
					if ((bool) $profile->mailOnEdited)
					{
						array_push($recipients, $profile->user->email);
					}
				}
			}
			else
			{
				/** @var User $userModel */
				$userModel = Container::getInstance('com_sdajem')->factory->model('User');
				/** @var array $userArray */
				$userArray = $userModel->getItemsArray();

				/** @var User $user */
				foreach ($userArray as $user)
				{
					array_push($recipients, $user->email);
				}
			}

			// User can decide on each event, if they want to have a mail
			/** @var Mailing $subscription */

			foreach ($event->subscriptions as $subscription)
			{
				if ((bool) $subscription->subscribed)
				{
					// This user don't want to be noticed on edited Events, but for this
					if (array_search($subscription->user->email, $recipients) === false)
					{
						array_push($recipients, $subscription->user->email);
					}
				}
				else
				{
					$arrayKey = array_search($subscription->user->email, $recipients);

					// User wants to be notice in general but not for this
					if (!($arrayKey === false))
					{
						array_forget($recipients, $arrayKey);
					}
				}
			}
		}

		return $recipients;
	}

	/**
	 * @param   array       $recipients Recipients
	 * @param   string      $subject    Subject
	 * @param   string      $body       Mail Body
	 *
	 * @return void
	 *
	 * @since   1.0
	 * @throws  Exception
	 */
	private function sendMail($recipients, $subject, $body)
	{
		if (!empty($recipients) && !empty($subject))
		{
			$mailer = Factory::getMailer();
			$mailer->setSender(array($this->mailFrom, $this->fromName));
			$mailer->setSubject($subject);

			if (empty($body))
			{
				$mailer->setBody(Text::_('PLG_SYSTEM_SDAMAILER_MAIL_NO_INFO'));
			}
			else
			{
				$mailer->setBody($body);
			}

			$mailer->addBcc($recipients);
			$send = $mailer->Send();

			if ($send !== true)
			{
				Factory::getApplication()->enqueueMessage(Text::_('PLG_SYSTEM_SDAMAILER_MAIL_SEND_ERROR'), 'error');
			}
			else
			{
				Factory::getApplication()->enqueueMessage(Text::_('PLG_SYSTEM_SDAMAILER_MAIL_SEND_SUCCESS'), 'message');
			}
		}
	}

}
