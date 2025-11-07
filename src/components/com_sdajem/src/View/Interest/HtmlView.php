<?php
/**
 * @package     Sda\Component\Sdajem\Site\View
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\View\Interest;

defined('_JEXEC') or die();

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use Sda\Component\Sdajem\Site\Model\AttendingModel;
use stdClass;

/**
 * @method getMVCFactory()
 * @since __BUM_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The page parameters
	 *
	 * @var    Registry|null
	 * @since  1.0.0
	 */
	protected ?Registry $params = null;

	/**
	 * The item model state
	 *
	 * @var    Registry
	 * @since  1.0.0
	 */
	protected Registry $state;

	/**
	 * The item object details
	 *
	 * @var    stdClass
	 * @since  1.0.0
	 */
	protected stdClass $item;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 * @throws Exception
	 * @since 1.0.0
	 */
	public function display($tpl = null)
	{
		/* @var AttendingModel model		 */
		$model = $this->getModel();
		$item = $this->item = $model->getItem();

		$state = $this->state = $model->getState();
		$params = $this->params = $state->get('params');

		/**
		 * $item->params are the event params, $temp are the menu item params
		 * Merge so that the menu item params take priority
		 *
		 * $itemparams->merge($temp);
		 */

		// Merge so that event params take priority
		$item->params = $params;

		$active = Factory::getApplication()->getMenu()->getActive();

		// Override the layout only if this is not the active menu item
		// If it is the active menu item, then the view and item id will match
		if ((!$active) || ((strpos($active->link, 'view=interest') === false) || (strpos($active->link, '&id=' . (string) $this->item->id) === false))) {
			if (($layout = $item->params->get('interests_layout'))) {
				$this->setLayout($layout);
			}
		} else if (isset($active->query['layout'])) {
			// We need to set the layout in case this is an alternative menu item (with an alternative layout)
			$this->setLayout($active->query['layout']);
		}

		Factory::getApplication()->triggerEvent('onContentPrepare', ['com_sdajem.interest', &$item, &$item->params]);

		// Store the events for later
		$item->event = new stdClass;
		$results = Factory::getApplication()->triggerEvent('onContentAfterTitle', ['com_sdajem.interest', &$item, &$item->params]);
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = Factory::getApplication()->triggerEvent('onContentBeforeDisplay', ['com_sdajem.interest', &$item, &$item->params]);
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = Factory::getApplication()->triggerEvent('onContentAfterDisplay', ['com_sdajem.interest', &$item, &$item->params]);
		$item->event->afterDisplayContent = trim(implode("\n", $results));

		$this->return_page = base64_encode(Uri::getInstance());

		return parent::display($tpl);
	}
}