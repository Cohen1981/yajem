<?php
/**
 * @package     Sda\Component\Sdajem\Site\View\Event
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\View\Event;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * @package     Sda\Component\Sdajem\Site\View\Event
 *
 * @since       __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The item object details
	 *
	 * @var    \JObject
	 * @since  __BUMP_VERSION__
	 */
	protected $item;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$item = $this->item = $this->get('Item');
		Factory::getApplication()->triggerEvent('onContentPrepare', ['com_sdajem.event', &$item, &$item->params]);
		// Store the events for later
		$item->event = new \stdClass;
		$results = Factory::getApplication()->triggerEvent('onContentAfterTitle', ['com_sdajem.event', &$item, &$item->params]);
		$item->event->afterDisplayTitle = trim(implode("\n", $results));
		$results = Factory::getApplication()->triggerEvent('onContentBeforeDisplay', ['com_sdajem.event', &$item, &$item->params]);
		$item->event->beforeDisplayContent = trim(implode("\n", $results));
		$results = Factory::getApplication()->triggerEvent('onContentAfterDisplay', ['com_sdajem.event', &$item, &$item->params]);
		$item->event->afterDisplayContent = trim(implode("\n", $results));

		return parent::display($tpl);
	}
}