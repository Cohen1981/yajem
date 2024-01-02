<?php
/**
 * @package     Sda\Component\Sdajem\Site\View
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\View\Event;

defined('_JEXEC') or die();

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Contact\Administrator\Extension\ContactComponent;
use Joomla\Component\Contact\Site\Model\ContactModel;
use Joomla\Registry\Registry;
use Sda\Component\Sdajem\Administrator\Model\FittingsModel;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Model\AttendingsModel;
use Sda\Component\Sdajem\Site\Model\EventModel;
use Sda\Component\Sdajem\Site\Model\InterestsModel;
use Sda\Component\Sdajem\Site\Model\LocationModel;
use Sda\Component\Sdajem\Site\Model\UserModel as SdaUserModel;
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
		/* @var EventModel $item */
		$item = $this->item = $this->get('Item');

		$state = $this->state = $this->get('State');
		$params = $this->params = $state->get('params');
		$itemparams = new Registry(json_decode($item->params));

		$temp = clone $params;

		/**
		 * $item->params are the foo params, $temp are the menu item params
		 * Merge so that the menu item params take priority
		 *
		 * $itemparams->merge($temp);
		 */

		// Merge so that foo params take priority
		$temp->merge($itemparams);
		$item->params = $temp;

		if (isset($item->organizerId))
		{
			$item->organizer = new SdaUserModel($item->organizerId);
		}

		if (isset($item->hostId))
		{
			/** @var ContactComponent $contactComponent */
			$contactComponent = Factory::getApplication()->bootComponent('com_contact');

			/** @var ContactModel $contactModel */
			$contactModel = $contactComponent->getMVCFactory()
				->createModel('Contact', 'Administrator', ['ignore_request' => true]);

			$temp = $contactModel->getItem($item->hostId);
			$temp->slug = $temp->alias ? ($temp->id . ':' . $temp->alias) : $temp->id;
			$item->host = $temp;

		}

		if($item->params->get('sda_use_attending')) {
			if($item->eventStatus == EventStatusEnum::PLANING->value)
			{
				/* @var InterestsModel $interests */
				$interests  = new InterestsModel();
				$interested = $interests->getInterestsToEvent($item->id);
				if ($interested)
				{
					$item->interests = $interested;
				}
			} else
			{
				/* @var AttendingsModel $interests */
				$interests = new AttendingsModel();
				$attendees = $interests->getAttendingsToEvent($item->id);
				if ($attendees)
				{
					$item->interests = $attendees;
				}

				if($item->params->get('sda_events_use_fittings'))
				{
					$fittingsModel = new FittingsModel();
					$fittings      = $fittingsModel->getFittingsForUser();
					if ($fittings)
					{
						$item->fittings = $fittings;
					}

					$eventFittings = $fittingsModel->getFittingsForEvent($item->id);
					if ($eventFittings) {
						$item->eventFittings = $eventFittings;
					}
				}
			}
		}

		if (isset($item->sdajem_location_id)) {
			$locationModel = new LocationModel();
			$item->location = $locationModel->getItem($item->sdajem_location_id);
		}

		$active = Factory::getApplication()->getMenu()->getActive();

		// Override the layout only if this is not the active menu item
		// If it is the active menu item, then the view and item id will match
		if ((!$active) || ((strpos($active->link, 'view=event') === false) || (strpos($active->link, '&id=' . (string) $this->item->id) === false))) {
			if (($layout = $item->params->get('events_layout'))) {
				$this->setLayout($layout);
			}
		} else if (isset($active->query['layout'])) {
			// We need to set the layout in case this is an alternative menu item (with an alternative layout)
			$this->setLayout($active->query['layout']);
		}

		Factory::getApplication()->triggerEvent('onContentPrepare', ['com_sdajem.event', &$item, &$item->params]);

		// Store the events for later
		$item->event = new stdClass;
		$results = Factory::getApplication()->triggerEvent('onContentAfterTitle', ['com_sdajem.event', &$item, &$item->params]);
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = Factory::getApplication()->triggerEvent('onContentBeforeDisplay', ['com_sdajem.event', &$item, &$item->params]);
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = Factory::getApplication()->triggerEvent('onContentAfterDisplay', ['com_sdajem.event', &$item, &$item->params]);
		$item->event->afterDisplayContent = trim(implode("\n", $results));

		$this->return_page = base64_encode(Uri::getInstance());

		return parent::display($tpl);
	}
}