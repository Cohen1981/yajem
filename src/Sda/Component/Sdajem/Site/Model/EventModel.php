<?php

/**
 * @copyright (c) 2025 Alexander Bahlo <abahlo@hotmail.de>
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sda\Component\Sdajem\Site\Model;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Sda\Component\Sdajem\Administrator\Library\Enums\EventStatusEnum;
use Sda\Component\Sdajem\Administrator\Library\Enums\IntAttStatusEnum;
use Sda\Component\Sdajem\Administrator\Library\Item\Event;

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
	/**
	 * @var Event
	 * @since 1.5.0
	 */
	protected Event $_item;

	/**
	 * Gets an event
	 *
	 * @since   1.0.0
	 *
	 * @param   int|null  $pk  pk for the event
	 *
	 * @return Event or null
	 * @throws Exception
	 */
	public function getItem(int $pk = null): Event
	{
		$app = Factory::getApplication();
		$pk  = ($pk) ? $pk : $app->input->getInt('id');

		if ($this->_item === null && $pk !== null)
		{
			try
			{
				$db = $this->getDatabase();
				$query = $db->getQuery(true);

				$query->select('a.*')
					->from($db->quoteName('#__sdajem_events', 'a'))
					->where($db->quoteName('a.id') . ' = :eventId');

				// Join over locations
				$query->select(
					$this->getState(
						'list.select',
						[
							$db->quoteName('loc.title', 'location_name'),
							$db->quoteName('loc.postalCode', 'postalCode')
						]
					)
				)
					->join(
						'LEFT',
						$db->quoteName('#__sdajem_locations', 'loc') . ' ON ' . $db->quoteName(
							'loc.id'
						) . ' = ' . $db->quoteName('a.sdajem_location_id')
					);

				// Join over User as organizer
				$query->select($db->quoteName('org.username', 'organizerName'))
					->join(
						'LEFT',
						$db->quoteName('#__users', 'org') . ' ON ' . $db->quoteName('org.id') . ' = ' . $db->quoteName(
							'a.organizerId'
						)
					);

				// Get the Attendee count
				$attendees = $db->getQuery(true)
					->select('COUNT(' . $db->quoteName('att.id') . ')')
					->from($db->quoteName('#__sdajem_attendings', 'att'))
					->where(
						[
							$db->quoteName('att.event_id') . ' = ' . $db->quoteName('a.id'),
							$db->quoteName('att.status') . ' = ' . IntAttStatusEnum::POSITIVE->value,
							$db->quoteName('att.event_status') . ' = ' . EventStatusEnum::OPEN->value
						]
					);
				$query->select('(' . $attendees . ') AS ' . $db->quoteName('attendeeCount'));

				// Get the guest count
				$guests = $db->getQuery(true)
					->select('COUNT(' . $db->quoteName('g.id') . ')')
					->from($db->quoteName('#__sdajem_attendings', 'g'))
					->where(
						[
							$db->quoteName('g.event_id') . ' = ' . $db->quoteName('a.id'),
							$db->quoteName('g.status') . ' = ' . IntAttStatusEnum::GUEST->value,
							$db->quoteName('g.event_status') . ' = ' . EventStatusEnum::OPEN->value
						]
					);
				$query->select('(' . $guests . ') AS ' . $db->quoteName('guestCount'));

				// Get the Attendee feedback count
				$attendeesf = $db->getQuery(true)
					->select('COUNT(' . $db->quoteName('atte.id') . ')')
					->from($db->quoteName('#__sdajem_attendings', 'atte'))
					->where(
						[
							$db->quoteName('atte.event_id') . ' = ' . $db->quoteName('a.id'),
							$db->quoteName(
								'atte.status'
							) . ' IN( ' . IntAttStatusEnum::POSITIVE->value . ',
							 ' . IntAttStatusEnum::NEGATIVE->value . ',
							  ' . IntAttStatusEnum::GUEST->value . ')',
							$db->quoteName('atte.event_status') . ' = ' . EventStatusEnum::OPEN->value
						]
					);
				$query->select('(' . $attendeesf . ') AS ' . $db->quoteName('attendeeFeedbackCount'));

				$interestCount = $db->getQuery(true)
					->select('COUNT(' . $db->quoteName('int.id') . ')')
					->from($db->quoteName('#__sdajem_attendings', 'int'))
					->where(
						[
							$db->quoteName('int.event_id') . ' = ' . $db->quoteName('a.id'),
							$db->quoteName('int.status') . ' = ' . IntAttStatusEnum::POSITIVE->value,
							$db->quoteName('int.event_status') . ' = ' . EventStatusEnum::PLANING->value
						]
					);
				$query->select('(' . $interestCount . ') AS ' . $db->quoteName('interestCount'));

				$feedback = $db->getQuery(true)
					->select('COUNT(' . $db->quoteName('i.id') . ')')
					->from($db->quoteName('#__sdajem_attendings', 'i'))
					->where(
						[
							$db->quoteName('i.event_id') . ' = ' . $db->quoteName('a.id'),
							$db->quoteName('i.event_status') . ' = ' . EventStatusEnum::PLANING->value
						]
					);
				$query->select('(' . $feedback . ') AS ' . $db->quoteName('feedbackCount'));

				// Join over the asset groups.
				$query->select($db->quoteName('ag.title', 'access_level'))
					->join(
						'LEFT',
						$db->quoteName('#__viewlevels', 'ag') . ' ON ' . $db->quoteName(
							'ag.id'
						) . ' = ' . $db->quoteName('a.access')
					);

				$query->bind(':eventId', $pk);

				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data))
				{
					throw new Exception(Text::_('COM_SDAJEM_ERROR_EVENT_NOT_FOUND'), 404);
				}

				$this->_item = Event::createFromObject($data);
			}
			catch (Exception $e)
			{
				$app->enqueueMessage($e->getMessage(), 'error');
				$this->_item = new Event;
			}
		}

		return $this->_item;
	}

	/**
	 * Method to autopopulate the model state.
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.0.0
	 * @return  void
	 * @throws Exception
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		$this->setState('event.id', $app->input->getInt('id'));
		$this->setState('params', $app->getParams());
	}
}
