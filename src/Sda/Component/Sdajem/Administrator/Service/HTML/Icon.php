<?php

/**
 * @copyright (c) 2025 Alexander Bahlo <abahlo@hotmail.de>
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sda\Component\Sdajem\Administrator\Service\HTML;

use Exception;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use Sda\Component\Sdajem\Administrator\Library\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Administrator\Library\Enums\IntAttStatusEnum;
use Sda\Component\Sdajem\Administrator\Library\Item\Attending;
use Sda\Component\Sdajem\Administrator\Library\Item\Event;
use Sda\Component\Sdajem\Administrator\Model\FittingModel;
use Sda\Component\Sdajem\Site\Helper\RouteHelper;
use stdClass;

use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;

// phpcs:enable PSR1.Files.SideEffects

class Icon
{
	/**
	 * The application
	 *
	 * @var    CMSApplication $application
	 * @since  __DEPLOY_VERSION__
	 */
	private $application;

	/**
	 * Service constructor
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   CMSApplication  $application  The application
	 */
	public function __construct(CMSApplication $application)
	{
		$this->application = $application;
	}

	/**
	 * Display an edit icon for the event.
	 * This icon will not display in a popup window, nor if the event is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Optional attributes for the link
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 * @param   object    $event    The event information
	 *
	 * @return  string   The HTML for the event edit icon.
	 * @throws Exception
	 */
	public static function edit(stdClass $event, Registry $params, array $attribs = [], bool $legacy = false): string
	{
		$uri = Uri::getInstance();

		// Ignore if in a popup window.
		if ($params && $params->get('popup'))
		{
			return '';
		}

		// Ignore if the state is negative (trashed).
		if ($event->published < 0)
		{
			return '';
		}

		// Set the link class
		$attribs['class'] = 'dropdown-item';

		if (!isset($event->slug))
		{
			$event->slug = "";
		}

		$eventUrl = RouteHelper::getEventRoute($event->slug, $event->catid, $event->language);
		$url      = $eventUrl . '&task=event.edit&id=' . $event->id . '&return=' . base64_encode($uri);

		if ($event->published == 0)
		{
			$overlib = Text::_('JUNPUBLISHED');
		}
		else
		{
			$overlib = Text::_('JPUBLISHED');
		}

		if (!isset($event->created))
		{
			$date = HTMLHelper::_('date', 'now');
		}
		else
		{
			$date = HTMLHelper::_('date', $event->created);
		}

		if (!isset($created_by_alias) && !isset($event->created_by))
		{
			$author = '';
		}
		else
		{
			$author = $event->created_by_alias ?: Factory::getApplication()->getIdentity($event->created_by)->name;
		}

		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= Text::sprintf('COM_SDAJEM_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));
		$icon    = $event->published ? 'edit' : 'eye-slash';

		$currentTimestamp = Factory::getDate()->format('Y-m-d H:i:s');

		if ((($event->publish_up > $currentTimestamp) && $event->publish_up != null)
			|| (($event->publish_down < $currentTimestamp) && $event->publish_down != null))
		{
			$icon = 'eye-slash';
		}

		$text             = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_('COM_SDAJEM_EDIT_EVENT'), $overlib, 0, 0) . '"></span> ';
		$text             .= Text::_('JGLOBAL_EDIT');
		$attribs['title'] = Text::_('COM_SDAJEM_EDIT_EVENT');
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		return $output;
	}

	/**
	 * Display an edit icon for the event.
	 * This icon will not display in a popup window, nor if the event is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   Registry  $params    The item parameters
	 * @param   array     $attribs   Optional attributes for the link
	 * @param   boolean   $legacy    True to use legacy images, false to use icomoon based graphic
	 * @param   object    $location  The event information
	 *
	 * @return  string   The HTML for the event edit icon.
	 * @throws Exception
	 */
	public static function editLocation($location, $params, $attribs = [], $legacy = false)
	{
		$uri = Uri::getInstance();
		// Ignore if in a popup window.
		if ($params && $params->get('popup'))
		{
			return '';
		}
		// Ignore if the state is negative (trashed).
		if ($location->published < 0)
		{
			return '';
		}

		if (!isset($location->slug))
		{
			$location->slug = "";
		}
		$locationUrl = RouteHelper::getLocationRoute($location->slug, $location->catid, $location->language);
		$url         = $locationUrl . '&task=location.edit&id=' . $location->id . '&return=' . base64_encode($uri);
		if ($location->published == 0)
		{
			$overlib = Text::_('JUNPUBLISHED');
		}
		else
		{
			$overlib = Text::_('JPUBLISHED');
		}
		if (!isset($location->created))
		{
			$date = HTMLHelper::_('date', 'now');
		}
		else
		{
			$date = HTMLHelper::_('date', $location->created);
		}
		if (!isset($created_by_alias) && !isset($location->created_by))
		{
			$author = '';
		}
		else
		{
			$author = Factory::getApplication()->getIdentity($location->created_by)->name;
		}
		$overlib          .= '&lt;br /&gt;';
		$overlib          .= $date;
		$overlib          .= '&lt;br /&gt;';
		$overlib          .= Text::sprintf('COM_FOOS_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));
		$icon             = $location->published ? 'edit' : 'eye-slash';
		$currentTimestamp = Factory::getDate()->format('Y-m-d H:i:s');
		if ((($location->publish_up > $currentTimestamp) && $location->publish_up != null)
			|| (($location->publish_down < $currentTimestamp) && $location->publish_down != null))
		{
			$icon = 'eye-slash';
		}
		$text             = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_('COM_SDAJEM_EDIT_LOCATION'), $overlib, 0, 0) . '"></span> ';
		$text             .= Text::_('JGLOBAL_EDIT');
		$attribs['title'] = Text::_('COM_SDAJEM_EDIT_LOCATION');
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		return $output;
	}

	/**
	 * @since 1.0.1
	 *
	 * @param          $params
	 * @param   array  $attribs
	 * @param   false  $legacy
	 * @param          $attending
	 *
	 * @return mixed|string
	 * @throws Exception
	 */
	public static function editAttending($attending, $params, $attribs = [], $legacy = false)
	{
		$uri = Uri::getInstance();
		// Ignore if in a popup window.
		if ($params && $params->get('popup'))
		{
			return '';
		}
		// Set the link class
		$attribs['class'] = 'dropdown-item';
		// Show checked_out icon if the event is checked out by a different user
		if (!isset($attending->slug))
		{
			$attending->slug = "";
		}
		$attendingUrl = 'index.php?option=com_sdajem&view=attending&id=' . $attending->id;
		$url          = $attendingUrl . '&task=attending.edit&id=' . $attending->id . '&return=' . base64_encode($uri);

		$icon = 'edit';

		$text             = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_('COM_FOOS_EDIT_ATTENDING'), '', 0, 0) . '"></span> ';
		$text             .= Text::_('JGLOBAL_EDIT');
		$attribs['title'] = Text::_('COM_SDAJEM_EDIT_ATTENDING');
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		return $output;
	}

	/**
	 * @since 1.0.0
	 *
	 * @param          $fittings
	 * @param          $params
	 * @param   array  $attribs
	 * @param   false  $legacy
	 * @param   Event  $event
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function register(Event $event, $fittings, $params, $attribs = [], $legacy = false)
	{
		$user = Factory::getApplication()->getIdentity();
		$uri  = Uri::getInstance();
		// Ignore if in a popup window.
		if ($params && $params->get('popup'))
		{
			return '';
		}
		// Ignore if the state is negative (trashed).
		if ($event->published < 0)
		{
			return '';
		}
		// Set the link class
		$attribs['class'] = 'dropdown-item';
		if (!isset($event->slug))
		{
			$event->slug = "";
		}

		$url = Route::_('index.php?option=com_sdajem');

		$text = '<form action="' . $url . '" method="post" id="adminForm" name="adminForm">';
		$text .= '<input type="hidden" name="event_id" value="' . $event->id . '"/>'
			. '<input type="hidden" name="return" value="' . base64_encode($uri) . '"/>'
			. '<input type="hidden" name="task" value=""/>';
		$text .= HTMLHelper::_('form.token');

		$interest = Attending::getAttendingToEvent($user->id, $event->id);

		if ($interest->status != IntAttStatusEnum::NA)
		{
			$text .= '<input type="hidden" name="attendingId" value="' . $interest->id . '"/>';
		}
		else
		{
			$interest         = new Attending();
			$interest->status = IntAttStatusEnum::NA;
		}

		$eventStatus = ($event->eventStatus === EventStatusEnum::PLANING->value) ? EventStatusEnum::PLANING : EventStatusEnum::OPEN;;

		foreach (IntAttStatusEnum::cases() as $status)
		{
			if ($status != IntAttStatusEnum::NA)
			{
				if ($eventStatus === EventStatusEnum::OPEN)
				{
					if ($status !== $interest->status || $interest->event_status === EventStatusEnum::PLANING)
					{
						$text .= '<button type="button" class="sda_button_spacer btn ' . $status->getButtonClass(
							) . '" onclick="Joomla.submitbutton(\'' . $status->getAction() . '\')">'
							. '<span class="icon-spacer ' . $status->getIcon() . '" aria-hidden="true"></span>';
						$text .= Text::_($status->getAttendingButtonLabel()) . '</button>';
					}
				}
				else
				{
					if ($status !== $interest->status)
					{
						$text .= '<button type="button" class="sda_button_spacer btn ' . $status->getButtonClass(
							) . '" onclick="Joomla.submitbutton(\'' . $status->getAction() . '\')">'
							. '<span class="icon-spacer ' . $status->getIcon() . '" aria-hidden="true"></span>';
						$text .= Text::_($status->getInterestButtonLabel()) . '</button>';
					}
				}
			}
		}

		$params = ComponentHelper::getParams('com_sdajem');
		$uf     = $params->get('sda_events_use_fittings');

		if ($uf && isset($fittings) && $interest->status != IntAttStatusEnum::POSITIVE && $event->eventStatus != EventStatusEnum::PLANING->value)
		{
			$text .= '<div class="sda_row"> <div class="sda_attendee_container">';
			/* @var FittingModel $fitting */
			foreach ($fittings as $i => $fitting)
			{
				$text .= '<div class="card" style="width: 120px;">';
				$text .= HTMLHelper::image($fitting->image, '');
				$text .= '<div class="card-body">';
				$text .= '<h5 class="card-title">' . $fitting->title . '</h5>';
				$text .= '<p class="card-text">' . $fitting->description . '</p>';
				$text .= '<input type="checkbox" name="fittings[]" value="' . $fitting->id . '"';
				if ($fitting->standard == 1)
				{
					$text .= ' checked="true"/>';
				}
				else
				{
					$text .= '/>';
				}
				$text .= '</div></div>';
			}
			$text .= '</div></div>';
		}

		$text   .= '</form>';
		$output = $text;

		return $output;
	}

	public static function editFitting($fitting, $params, $attribs = [], $legacy = false)
	{
		$uri = Uri::getInstance();
		// Ignore if in a popup window.
		if ($params && $params->get('popup'))
		{
			return '';
		}
		// Set the link class
		$attribs['class'] = 'dropdown-item';

		$attendingUrl = 'index.php?option=com_sdajem&view=fitting&layout=edit&id=' . $fitting->id;
		$url          = $attendingUrl . '&task=fitting.edit&id=' . $fitting->id . '&return=' . base64_encode($uri);

		$icon = 'edit';

		$text             = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_('COM_SDAJEM_EDIT_FITTING'), '', 0, 0) . '"></span> ';
		$text             .= Text::_('JGLOBAL_EDIT');
		$attribs['title'] = Text::_('COM_SDAJEM_EDIT_FITTING');
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		return $output;
	}

	public static function switchEventStatus($event, EventStatusEnum $action, $attribs = [])
	{
		$uri = Uri::getInstance();

		// Set the link class
		// $attribs['class'] = 'sda_button_spacer btn btn-light';
		$attribs['class'] = 'dropdown-item';

		$eventUrl = 'index.php?option=com_sdajem&view=events';
		$url      = $eventUrl . '&task=' . $action->getEventAction(
			) . '&eventId=' . $event->id . '&return=' . base64_encode($uri);

		$icon = $action->getIcon();

		$text             = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(
				Text::_($action->getStatusLabel()),
				'',
				0,
				0
			) . '">&nbsp;</span><span class="icon-text">' . Text::_($action->getStatusLabel()) . '</span> ';
		$attribs['title'] = Text::_($action->getStatusLabel());
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		return $output;
	}
}
