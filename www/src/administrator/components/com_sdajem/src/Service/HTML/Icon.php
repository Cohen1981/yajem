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
use Sda\Component\Sdajem\Site\Helper\RouteHelper;
use Joomla\Registry\Registry;

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
		if (strtotime($event->publish_up) > strtotime(Factory::getDate())
			|| ((strtotime($event->publish_down) < strtotime(Factory::getDate())) && $event->publish_down != Factory::getDbo()->getNullDate())) {
			$icon = 'eye-slash';
		}
		$text = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_('COM_FOOS_EDIT_FOO'), $overlib, 0, 0) . '"></span> ';
		$text .= Text::_('JGLOBAL_EDIT');
		$attribs['title'] = Text::_('COM_FOOS_EDIT_FOO');
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);
		return $output;
	}
}