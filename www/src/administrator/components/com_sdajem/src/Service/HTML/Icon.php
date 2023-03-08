<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Service\HTML
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Service\HTML;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Sda\Component\Sdajem\Site\Enums\AttendingStatusEnum;
use Sda\Component\Sdajem\Site\Helper\RouteHelper;
use Joomla\Registry\Registry;
use Sda\Component\Sdajem\Site\Model\AttendingsModel;

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
	 * Method to generate a link to the create item page for the given category
	 *
	 * @param   object    $category  The category information
	 * @param   Registry  $params    The item parameters
	 * @param   array     $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the create item link
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function create($category, $params, $attribs = [])
	{
		$uri = Uri::getInstance();
		$url = 'index.php?option=com_sdajem&task=event.add&return=' . base64_encode($uri) . '&id=0&catid=' . $category->id;
		$text = LayoutHelper::render('joomla.content.icons.create', ['params' => $params, 'legacy' => false]);
		// Add the button classes to the attribs array
		if (isset($attribs['class'])) {
			$attribs['class'] .= ' btn btn-primary';
		} else {
			$attribs['class'] = 'btn btn-primary';
		}
		$button = HTMLHelper::_('link', Route::_($url), $text, $attribs);
		$output = '<span class="hasTooltip" title="' . HTMLHelper::_('tooltipText', 'COM_FOOS_CREATE_FOO') . '">' . $button . '</span>';
		return $output;
	}
	/**
	 * Display an edit icon for the event.
	 *
	 * This icon will not display in a popup window, nor if the event is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @param   object    $event  The event information
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Optional attributes for the link
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string   The HTML for the event edit icon.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function edit($event, $params, $attribs = [], $legacy = false)
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
		// Show checked_out icon if the event is checked out by a different user
		if (property_exists($event, 'checked_out')
			&& property_exists($event, 'checked_out_time')
			&& $event->checked_out > 0
			&& $event->checked_out != $user->get('id')) {
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
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Optional attributes for the link
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string   The HTML for the event edit icon.
	 *
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
			&& $location->checked_out != $user->get('id')) {
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
	 * @throws \Exception
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
	 * @param          $event
	 * @param          $params
	 * @param   array  $attribs
	 * @param   false  $legacy
	 *
	 * @return string
	 *
	 * @throws \Exception
	 * @since 1.0.0
	 *
	 */
	public static function register($event, $params, $attribs = [], $legacy = false)
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
		$attendingsModel = new AttendingsModel();
		$attending = $attendingsModel->getAttendingStatusToEvent($event->id);

		if ($attending) {
			$text .= '<input type="hidden" name="attendingId" value="' . $attending->id . '"/>';
		} else {
			$attending = new \stdClass();
			$attending->status = AttendingStatusEnum::NA->value;
		}

		foreach (AttendingStatusEnum::cases() as $status) {
			if ($status != AttendingStatusEnum::from($attending->status) && $status != AttendingStatusEnum::NA)
			{
				$text .= '<button type="button" class="sda_button_spacer btn ' . $status->getButtonClass() . '" onclick="Joomla.submitbutton(\'' . $status->getAction() . '\')">'
						. '<span class="icon-spacer ' . $status->getIcon() . '" aria-hidden="true"></span>';
				$text .= Text::_($status->getButtonLabel()) . '</button>';
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
}