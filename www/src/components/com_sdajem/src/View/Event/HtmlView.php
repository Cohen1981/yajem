<?php
/**
 * @package     Sda\Component\Sdajem\Site\View
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\View\Event;

use Exception;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\User\UserFactory;
use Joomla\CMS\User\UserFactoryInterface;
use Joomla\CMS\User\UserHelper;
use Joomla\Component\Contact\Administrator\Extension\ContactComponent;
use Joomla\Component\Contact\Site\Model\ContactModel;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Component\Users\Administrator\Extension\UsersComponent;
use Joomla\Component\Users\Administrator\Model\UserModel;
use Joomla\Registry\Registry;
use Sda\Component\Sdajem\Site\Model\AttendingsModel;
use Sda\Component\Sdajem\Site\Model\EventModel;
use stdClass;

defined('_JEXEC') or die;

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
	 * @since  __BUMP_VERSION__
	 */
	protected ?Registry $params = null;

	/**
	 * The item model state
	 *
	 * @var    CMSObject
	 * @since  __BUMP_VERSION__
	 */
	protected CMSObject $state;

	/**
	 * The item object details
	 *
	 * @var    stdClass
	 * @since  __BUMP_VERSION__
	 */
	protected stdClass $item;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 * @throws Exception
	 * @since __BUMP_VERSION__
	 */
	public function display($tpl = null)
	{
		/* @var EventModel $item */
		$item = $this->item = $this->get('Item');

		$state = $this->state = $this->get('State');
		$params = $this->params = $state->get('params');

		if (isset($item->organizerId))
		{
			$item->organizer = Factory::getContainer()->get(UserFactoryInterface::class)->loadUserById($item->organizerId);

			$item->organizer->profile = UserHelper::getProfile($item->organizerId);

			$userdata = FieldsHelper::getFields('com_users.user', $item->organizer, true);
			$tmp          = isset($userdata) ? $userdata : array();
			$customFields = array();

			foreach ($tmp as $customField)
			{
				$customFields[$customField->name] = $customField;
			}
			$item->organizer->userData = $customFields;

			/** @var UsersComponent $userComponent */
			#$userComponent = Factory::getApplication()->bootComponent('com_users');

			/** @var UserModel $userModel */
			#$userModel = $userComponent->getMVCFactory()->createModel('User', 'Administrator', ['ignore_request' => true]);

			#$item->organizer = $userModel->getItem($item->organizerId);
		}

		if (isset($item->hostId))
		{
			/** @var ContactComponent $contactComponent */
			$contactComponent = Factory::getApplication()->bootComponent('com_contact');

			/** @var ContactModel $contactModel */
			$contactModel = $contactComponent->getMVCFactory()
				->createModel('Contact', 'Administrator', ['ignore_request' => true]);

			$item->host = $contactModel->getItem($item->hostId);
		}
		/**
		 * $item->params are the event params, $temp are the menu item params
		 * Merge so that the menu item params take priority
		 *
		 * $itemparams->merge($temp);
		 */

		// Merge so that event params take priority
		$item->params = $params;

		if($item->params->get('sda_use_attending')) {
			/* @var AttendingsModel $attendings */
			$attendings = new AttendingsModel();
			$attendees = $attendings->getAttendingsToEvent($item->id);
			if ($attendees) {
				$item->attendings = $attendees;
			}
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

		return parent::display($tpl);
	}
}