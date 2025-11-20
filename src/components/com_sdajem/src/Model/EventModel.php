<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Sda\Component\Sdajem\Site\Enums\IntAttStatusEnum;
use Sda\Component\Sdajem\Site\Model\Item\Event;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Event model for the Joomla Events component.
 *
 * @since  1.0.0
 */

class EventModel extends BaseDatabaseModel
{
	protected Event|null $_item = null;

	/**
	 * Gets an event
	 *
	 * @param   int|null  $pk  pk for the event
	 *
	 * @return Event or null
	 *
	 * @throws Exception
	 * @since   1.0.0
	 */
	public function getItem(int $pk = null): Event
	{
		$app = Factory::getApplication();
		$pk  = ($pk) ? $pk : $app->input->getInt('id');

		if ($this->_item === null && $pk !== null)
		{
			try
			{
				$db    = $this->getDatabase();
				$query = $db->getQuery(true);

				$query->select('a.*')
					->from($db->quoteName('#__sdajem_events', 'a'))
					->where($db->quoteName('a.id') . ' = :eventId');
				// Join over locations
				$query->select($this->getState(
					'list.select',
					[
						$db->quoteName('loc.title', 'location_name'),
						$db->quoteName('loc.postalCode', 'postalCode')
						]
					)
				)
					->join(
						'LEFT',
						$db->quoteName('#__sdajem_locations', 'loc') . ' ON ' . $db->quoteName('loc.id') . ' = ' . $db->quoteName('a.sdajem_location_id')
					);
				//Join over User as organizer
				$query->select($db->quoteName('org.username', 'organizerName'))
					->join(
						'LEFT',
						$db->quoteName('#__users', 'org') . ' ON ' . $db->quoteName('org.id') . ' = ' . $db->quoteName('a.organizerId')
					);

				//Get the Attendee count
				$attendees = $db->getQuery(true)
					->select('COUNT(' . $db->quoteName('att.id') . ')')
					->from($db->quoteName('#__sdajem_attendings', 'att'))
					->where(
						[
							$db->quoteName('att.event_id') . ' = ' . $db->quoteName('a.id'),
							$db->quoteName('att.status') . ' = ' . IntAttStatusEnum::POSITIVE->value,
						]
					);
				$query->select('(' . $attendees . ') AS ' . $db->quoteName('attendeeCount'));

				//Get the guest count
				$guests = $db->getQuery(true)
					->select('COUNT(' . $db->quoteName('g.id') . ')')
					->from($db->quoteName('#__sdajem_attendings', 'g'))
					->where(
						[
							$db->quoteName('g.event_id') . ' = ' . $db->quoteName('a.id'),
							$db->quoteName('g.status') . ' = ' . IntAttStatusEnum::GUEST->value,
						]
					);
				$query->select('(' . $guests . ') AS ' . $db->quoteName('guestCount'));

				//Get the Attendee feedback count
				$attendeesf = $db->getQuery(true)
					->select('COUNT(' . $db->quoteName('atte.id') . ')')
					->from($db->quoteName('#__sdajem_attendings', 'atte'))
					->where(
						[
							$db->quoteName('atte.event_id') . ' = ' . $db->quoteName('a.id'),
							$db->quoteName('atte.status') . ' IN( ' . IntAttStatusEnum::POSITIVE->value . ', ' . IntAttStatusEnum::NEGATIVE->value . ', ' . IntAttStatusEnum::GUEST->value . ')',
						]
					);
				$query->select('(' . $attendeesf . ') AS ' . $db->quoteName('attendeeFeedbackCount'));

				$interestCount = $db->getQuery(true)
					->select('COUNT(' . $db->quoteName('int.id') . ')')
					->from($db->quoteName('#__sdajem_interest', 'int'))
					->where(
						[
							$db->quoteName('int.event_id') . ' = ' . $db->quoteName('a.id'),
							$db->quoteName('int.status') . ' = ' . IntAttStatusEnum::POSITIVE->value,
						]
					);
				$query->select('(' . $interestCount . ') AS ' . $db->quoteName('interestCount'));

				$feedback = $db->getQuery(true)
					->select('COUNT(' . $db->quoteName('i.id') . ')')
					->from($db->quoteName('#__sdajem_interest', 'i'))
					->where(
						[
							$db->quoteName('i.event_id') . ' = ' . $db->quoteName('a.id'),
						]
					);
				$query->select('(' . $feedback . ') AS ' . $db->quoteName('feedbackCount'));
				// Join over the asset groups.
				$query->select($db->quoteName('ag.title', 'access_level'))
					->join(
						'LEFT',
						$db->quoteName('#__viewlevels', 'ag') . ' ON ' . $db->quoteName('ag.id') . ' = ' . $db->quoteName('a.access')
					);

				$query->bind(':eventId', $pk);
					#->where('a.id = ' . (int) $pk);

				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data))
				{
					throw new Exception(Text::_('COM_SDAJEM_ERROR_EVENT_NOT_FOUND'), 404);
				}

				if($data->svg)
					$data->svg = (array) json_decode($data->svg);
				else
					$data->svg = array();
				$this->_item = Event::createFromObject($data);
			}
			catch (Exception $e)
			{
				$app->enqueueMessage($e->getMessage(),'error');
				$this->_item = new Event();
			}
		}

		return $this->_item;
	}

	/**
	 * Method to autopopulate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @throws Exception
	 *
	 * @since   1.0.0
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		$this->setState('event.id', $app->input->getInt('id'));
		$this->setState('params', $app->getParams());
	}
}