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
use Joomla\CMS\Document\Document;
use Joomla\CMS\Event\Content\AfterDisplayEvent;
use Joomla\CMS\Event\Content\AfterTitleEvent;
use Joomla\CMS\Event\Content\BeforeDisplayEvent;
use Joomla\CMS\Event\Content\ContentPrepareEvent;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Contact\Administrator\Extension\ContactComponent;
use Joomla\Component\Contact\Site\Model\ContactModel;
use Joomla\Registry\Registry;
use Sda\Component\Sdajem\Administrator\Model\FittingsModel;
use Sda\Component\Sdajem\Site\Model\Collections\CommentsCollection;
use Sda\Component\Sdajem\Site\Model\Item\Event;
use Sda\Component\Sdajem\Site\Model\Item\Location;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Site\Model\AttendingsModel;
use Sda\Component\Sdajem\Site\Model\CommentsModel;
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

	public ?Event $item;
	public ?Location $location;
	public ?SdaUserModel $organizer;
	public ?ContactModel $host;
	public ?array $interests = [];
	public ?array $userFittings = [];
	public ?array $eventFittings =[];
	public ?CommentsCollection $comments;
	public string $activeAccordion = 'event.location';

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	public function display($tpl = null)
	{
		/* @var EventModel $model */
		$model = $this->getModel();

		$app = Factory::getApplication();

		$item = $this->item = $model->getItem();

		$state = $this->state = $model->getState();
		$params = $this->params = $state->get('params');
		$itemparams = new Registry(json_decode($item->params));

		$activeAccordion = $app->getUserState('com_sdajem.event.callContext', $this->activeAccordion);
		$this->activeAccordion = $activeAccordion;

		$temp = clone $params;

		/**
		 * $item->params are the foo params, $temp are the menu item params
		 * Merge so that the menu item params take priority
		 *
		 * $itemparams->merge($temp);
		 */

		// Merge so that foo params take priority
		$temp->merge($itemparams);
		$item->paramsRegistry = $temp;

		if (isset($item->organizerId))
		{
			$this->organizer = new SdaUserModel($item->organizerId);
		}

		if (isset($item->hostId))
		{
			/** @var ContactComponent $contactComponent */
			$contactComponent = Factory::getApplication()->bootComponent('com_contact');

			$contactModel = $contactComponent->getMVCFactory()
				->createModel('Contact', 'Administrator', ['ignore_request' => true]);

			$temp = $contactModel->getItem($item->hostId);
			$temp->slug = $temp->alias ? ($temp->id . ':' . $temp->alias) : $temp->id;
			$this->host = $temp;

		}

		if($item->paramsRegistry->get('sda_events_use_comments')) {
			$this->setModel(new CommentsModel());
			$this->comments = new CommentsCollection($this->getModel('comments')->getCommentsToEvent($item->id));
		}

		if($item->paramsRegistry->get('sda_use_attending')) {
			if($item->eventStatus == EventStatusEnum::PLANING->value)
			{
				$this->setModel(new InterestsModel());
				$interested = $this->getModel('interests')->getInterestsToEvent($item->id);
				if ($interested)
				{
					$this->interests = $interested;
				}
			} else
			{
				$this->setModel(new AttendingsModel());
				$this->interests = $this->getModel('attendings')->getAttendingsToEvent($item->id);

				if($item->paramsRegistry->get('sda_events_use_fittings'))
				{
					$this->setModel(new FittingsModel());
					$this->userFittings = $this->getModel('fittings')->getFittingsForUser();
					$this->eventFittings = $this->getModel('fittings')->getFittingsForEvent($item->id);
				}
			}
		}

		if (isset($item->sdajem_location_id)) {
			$this->setModel(new LocationModel());
			$this->location = $this->getModel('location')->getItem($item->sdajem_location_id);
		}

		$active = Factory::getApplication()->getMenu()->getActive();

		// Override the layout only if this is not the active menu item
		// If it is the active menu item, then the view and item id will match
		if ((!$active) || ((strpos($active->link, 'view=event') === false) || (strpos($active->link, '&id=' . (string) $this->item->id) === false))) {
			if (($layout = $item->paramsRegistry->get('events_layout'))) {
				$this->setLayout($layout);
			}
		} else if (isset($active->query['layout'])) {
			// We need to set the layout in case this is an alternative menu item (with an alternative layout)
			$this->setLayout($active->query['layout']);
		}
		if ($this->_layout === 'planning_modal')
		{
			$this->setLayout('planning_modal');
		}

		$contentEventArguments = [
			'context' => 'com_sdajem.event',
			'subject' => &$item,
			'params'  => &$item->paramsRegistry
		];

		$dispatcher = $this->getDispatcher();
		$dispatcher->dispatch('onContentPrepare', new ContentPrepareEvent('onContentPrepare', $contentEventArguments));

		// Store the events for later
		$item->event = new stdClass;

		$contentEvents = [
			'afterDisplayTitle'    => new AfterTitleEvent('onContentAfterTitle', $contentEventArguments),
			'beforeDisplayContent' => new BeforeDisplayEvent('onContentBeforeDisplay', $contentEventArguments),
			'afterDisplayContent'  => new AfterDisplayEvent('onContentAfterDisplay', $contentEventArguments),
		];

		foreach ($contentEvents as $resultKey => $event) {
			$results = $dispatcher->dispatch($event->getName(), $event)->getArgument('result', []);

			$item->event->{$resultKey} = $results ? trim(implode("\n", $results)) : '';
		}

		$this->return_page = base64_encode(Uri::getInstance());

		parent::display($tpl);
	}

	public function getDocument():Document
	{
		return parent::getDocument();
	}
}