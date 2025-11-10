<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @package     Sda\Component\Sdajem\Administrator\Service\HTML
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Service\HTML;

use Exception;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\User;
use Joomla\Registry\Registry;
use Sda\Component\Sdajem\Administrator\Helper\AttendingHelper;
use Sda\Component\Sdajem\Administrator\Helper\InterestHelper;
use Sda\Component\Sdajem\Administrator\Model\FittingModel;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Enums\IntAttStatusEnum;
use Sda\Component\Sdajem\Site\Helper\RouteHelper;
use Sda\Component\Sdajem\Site\Model\Item\Event;
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
	 * @var    CMSApplication
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	private $application;
	/**
	 * Service constructor
	 *
	 * @param   CMSApplication  $application  The application
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(CMSApplication $application)
	{
		$this->application = $application;
	}

	/**
	 * Display an edit icon for the event.
	 *
	 * This icon will not display in a popup window, nor if the event is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @param   object    $event    The event information
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Optional attributes for the link
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string   The HTML for the event edit icon.
	 *
	 * @throws Exception
	 * @since   __DEPLOY_VERSION__
	 */
	public static function edit($event, $params, $attribs = [], $legacy = false)
	{
		/**
		 * @var User $user
		 */
		$user = Factory::getApplication()->getIdentity();
		$uri  = Uri::getInstance();
		// Ignore if in a popup window.
		if ($params && $params->get('popup')) {
			return '';
		}
		// Ignore if the state is negative (trashed).
		if ($event->published < 0) {
			return '';
		}
		// Set the link class
		$attribs['class'] = 'dropdown-item';
		// Show checked_out icon if the event is checked out by a different user
		if (property_exists($event, 'checked_out')
			&& property_exists($event, 'checked_out_time')
			&& $event->checked_out > 0
			&& $event->checked_out != $user->id) {
			$checkoutUser = Factory::getApplication()->getIdentity($event->checked_out);
			$date         = HTMLHelper::_('date', $event->checked_out_time);
			$tooltip      = Text::_('JLIB_HTML_CHECKED_OUT') . ' :: ' . Text::sprintf('COM_FOOS_CHECKED_OUT_BY', $checkoutUser->name)
				. ' <br /> ' . $date;
			$text = LayoutHelper::render('joomla.content.icons.edit_lock', ['tooltip' => $tooltip, 'legacy' => $legacy]);
			$output = HTMLHelper::_('link', '#', $text, $attribs);
			return $output;
		}
		if (!isset($event->slug)) {
			$event->slug = "";
		}
		$eventUrl = RouteHelper::getEventRoute($event->slug, $event->catid, $event->language);
		$url        = $eventUrl . '&task=event.edit&id=' . $event->id . '&return=' . base64_encode($uri);
		if ($event->published == 0) {
			$overlib = Text::_('JUNPUBLISHED');
		} else {
			$overlib = Text::_('JPUBLISHED');
		}
		if (!isset($event->created)) {
			$date = HTMLHelper::_('date', 'now');
		} else {
			$date = HTMLHelper::_('date', $event->created);
		}
		if (!isset($created_by_alias) && !isset($event->created_by)) {
			$author = '';
		} else {
			$author = $event->created_by_alias ?: Factory::getApplication()->getIdentity($event->created_by)->name;
		}
		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= Text::sprintf('COM_FOOS_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));
		$icon = $event->published ? 'edit' : 'eye-slash';
		if ((strtotime($event->publish_up) > strtotime(Factory::getDate()) && $event->publish_up != null)
			|| ((strtotime($event->publish_down) < strtotime(Factory::getDate())) && $event->publish_down != null)) {
			$icon = 'eye-slash';
		}
		$text = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_('COM_FOOS_EDIT_FOO'), $overlib, 0, 0) . '"></span> ';
		$text .= Text::_('JGLOBAL_EDIT');
		$attribs['title'] = Text::_('COM_FOOS_EDIT_FOO');
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);
		return $output;
	}

	/**
	 * Display an edit icon for the event.
	 *
	 * This icon will not display in a popup window, nor if the event is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @param   object    $location  The event information
	 * @param   Registry  $params    The item parameters
	 * @param   array     $attribs   Optional attributes for the link
	 * @param   boolean   $legacy    True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string   The HTML for the event edit icon.
	 *
	 * @throws Exception
	 * @since   __DEPLOY_VERSION__
	 */
	public static function editLocation($location, $params, $attribs = [], $legacy = false)
	{
		$user = Factory::getApplication()->getIdentity();
		$uri  = Uri::getInstance();
		// Ignore if in a popup window.
		if ($params && $params->get('popup')) {
			return '';
		}
		// Ignore if the state is negative (trashed).
		if ($location->published < 0) {
			return '';
		}

		// Show checked_out icon if the event is checked out by a different user
		if (property_exists($location, 'checked_out')
			&& property_exists($location, 'checked_out_time')
			&& $location->checked_out > 0
			&& $location->checked_out != $user->id) {
			$checkoutUser = Factory::getApplication()->getIdentity($location->checked_out);
			$date         = HTMLHelper::_('date', $location->checked_out_time);
			$tooltip      = Text::_('JLIB_HTML_CHECKED_OUT') . ' :: ' . Text::sprintf('COM_FOOS_CHECKED_OUT_BY', $checkoutUser->name)
				. ' <br /> ' . $date;
			$text = LayoutHelper::render('joomla.content.icons.edit_lock', ['tooltip' => $tooltip, 'legacy' => $legacy]);
			$output = HTMLHelper::_('link', '#', $text, $attribs);
			return $output;
		}
		if (!isset($location->slug)) {
			$location->slug = "";
		}
		$locationUrl = RouteHelper::getLocationRoute($location->slug, $location->catid, $location->language);
		$url        = $locationUrl . '&task=location.edit&id=' . $location->id . '&return=' . base64_encode($uri);
		if ($location->published == 0) {
			$overlib = Text::_('JUNPUBLISHED');
		} else {
			$overlib = Text::_('JPUBLISHED');
		}
		if (!isset($location->created)) {
			$date = HTMLHelper::_('date', 'now');
		} else {
			$date = HTMLHelper::_('date', $location->created);
		}
		if (!isset($created_by_alias) && !isset($location->created_by)) {
			$author = '';
		} else {
			$author = Factory::getApplication()->getIdentity($location->created_by)->name;
		}
		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= Text::sprintf('COM_FOOS_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));
		$icon = $location->published ? 'edit' : 'eye-slash';
		if ((strtotime($location->publish_up) > strtotime(Factory::getDate()) && $location->publish_up != null)
			|| ((strtotime($location->publish_down) < strtotime(Factory::getDate())) && $location->publish_down != null)) {
			$icon = 'eye-slash';
		}
		$text = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_('COM_SDAJEM_EDIT_LOCATION'), $overlib, 0, 0) . '"></span> ';
		$text .= Text::_('JGLOBAL_EDIT');
		$attribs['title'] = Text::_('COM_SDAJEM_EDIT_LOCATION');
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);
		return $output;
	}

	/**
	 * @param          $attending
	 * @param          $params
	 * @param   array  $attribs
	 * @param   false  $legacy
	 *
	 * @return mixed|string
	 *
	 * @throws Exception
	 * @since 1.0.1
	 */
	public static function editAttending($attending, $params, $attribs = [], $legacy = false) {
		$user = Factory::getApplication()->getIdentity();
		$uri  = Uri::getInstance();
		// Ignore if in a popup window.
		if ($params && $params->get('popup')) {
			return '';
		}
		// Set the link class
		$attribs['class'] = 'dropdown-item';
		// Show checked_out icon if the event is checked out by a different user
		if (!isset($attending->slug)) {
			$attending->slug = "";
		}
		$attendingUrl = 'index.php?option=com_sdajem&view=attending&id=' . $attending->id;
		$url        = $attendingUrl . '&task=attending.edit&id=' . $attending->id . '&return=' . base64_encode($uri);

		$icon = 'edit';

		$text = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_('COM_FOOS_EDIT_ATTENDING'), '', 0, 0) . '"></span> ';
		$text .= Text::_('JGLOBAL_EDIT');
		$attribs['title'] = Text::_('COM_SDAJEM_EDIT_ATTENDING');
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);
		return $output;
	}

	/**
	 * @param   Event  $event
	 * @param          $fittings
	 * @param          $params
	 * @param   array  $attribs
	 * @param   false  $legacy
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	public static function register(Event $event, $fittings, $params, $attribs = [], $legacy = false)
	{
		$user = Factory::getApplication()->getIdentity();
		$uri  = Uri::getInstance();
		// Ignore if in a popup window.
		if ($params && $params->get('popup')) {
			return '';
		}
		// Ignore if the state is negative (trashed).
		if ($event->published < 0) {
			return '';
		}
		// Set the link class
		$attribs['class'] = 'dropdown-item';
		if (!isset($event->slug)) {
			$event->slug = "";
		}

		$url      = Route::_('index.php?option=com_sdajem');

		$icon = 'add';

		$text = '<form action="' . $url . '" method="post" id="adminForm" name="adminForm">';
		$text .= '<input type="hidden" name="event_id" value="' . $event->id . '"/>'
				. '<input type="hidden" name="return" value="' . base64_encode($uri) . '"/>'
				. '<input type="hidden" name="task" value=""/>';
		$text .= HTMLHelper::_('form.token');
		// Test for attending Status

		if ($event->eventStatus == EventStatusEnum::PLANING->value) {
			$interest = InterestHelper::getInterestStatusToEvent(Factory::getApplication()->getIdentity()->id,
				$event->id);

			if ($interest)
			{
				$text .= '<input type="hidden" name="interestId" value="' . $interest->id . '"/>';
			}
			else
			{
				$interest         = new stdClass();
				$interest->status = IntAttStatusEnum::NA->value;
			}

			foreach (IntAttStatusEnum::cases() as $status)
			{
				if ($status != IntAttStatusEnum::from($interest->status) && $status != IntAttStatusEnum::NA)
				{
					$text .= '<button type="button" class="sda_button_spacer btn ' . $status->getButtonClass() . '" onclick="Joomla.submitbutton(\'' . $status->getAction() . '\')">'
						. '<span class="icon-spacer ' . $status->getIcon() . '" aria-hidden="true"></span>';
					$text .= Text::_($status->getInterestButtonLabel()) . '</button>';
				}
			}

			$text .= '<div class="sda_row"><input type="text" name="comment" id="comment" size="100" placeholder="' . Text::_('COM_SDAJEM_INTEREST_COMMENT') . '"/></div>';
		}
		else
		{
			$interest = AttendingHelper::getAttendingStatusToEvent($user->id, $event->id);

			if ($interest->status != IntAttStatusEnum::NA->value)
			{
				$text .= '<input type="hidden" name="attendingId" value="' . $interest->id . '"/>';
			}
			else
			{
				$interest         = new stdClass();
				$interest->status = IntAttStatusEnum::NA->value;
			}

			foreach (IntAttStatusEnum::cases() as $status)
			{
				if ($status != IntAttStatusEnum::from($interest->status) && $status != IntAttStatusEnum::NA)
				{
					$text .= '<button type="button" class="sda_button_spacer btn ' . $status->getButtonClass() . '" onclick="Joomla.submitbutton(\'' . $status->getAction() . '\')">'
						. '<span class="icon-spacer ' . $status->getIcon() . '" aria-hidden="true"></span>';
					$text .= Text::_($status->getAttendingButtonLabel()) . '</button>';
				}
			}

			$params = ComponentHelper::getParams('com_sdajem');
			$uf = $params->get('sda_events_use_fittings');

			if ($uf && isset($fittings) && AttendingHelper::getAttendingStatusToEvent($user->id, $event->id)->status != IntAttStatusEnum::POSITIVE->value) {
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
		}

		$text .= '</form>';
        $output = $text;

		return $output;
	}

	public static function editFitting($fitting, $params, $attribs = [], $legacy = false) {
		$user = Factory::getApplication()->getIdentity();
		$uri  = Uri::getInstance();
		// Ignore if in a popup window.
		if ($params && $params->get('popup')) {
			return '';
		}
		// Set the link class
		$attribs['class'] = 'dropdown-item';

		$attendingUrl = 'index.php?option=com_sdajem&view=fitting&layout=edit&id=' . $fitting->id;
		$url        = $attendingUrl . '&task=fitting.edit&id=' . $fitting->id . '&return=' . base64_encode($uri);

		$icon = 'edit';

		$text = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_('COM_SDAJEM_EDIT_FITTING'), '', 0, 0) . '"></span> ';
		$text .= Text::_('JGLOBAL_EDIT');
		$attribs['title'] = Text::_('COM_SDAJEM_EDIT_FITTING');
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);
		return $output;
	}

	public static function switchEventStatus($event, EventStatusEnum $action, $attribs = []) {
		$uri  = Uri::getInstance();

		// Set the link class
		// $attribs['class'] = 'sda_button_spacer btn btn-light';
		$attribs['class']= 'dropdown-item';

		$eventUrl = 'index.php?option=com_sdajem&view=events';
		$url        = $eventUrl . '&task='. $action->getEventAction() . '&eventId=' . $event->id . '&return=' . base64_encode($uri);

		$icon = $action->getIcon();

		$text = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_($action->getStatusLabel()), '', 0, 0) . '">&nbsp;</span><span class="icon-text">' . Text::_($action->getStatusLabel()) . '</span> ';
		$attribs['title'] = Text::_($action->getStatusLabel());
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);
		return $output;
	}
}