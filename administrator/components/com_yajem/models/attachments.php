<?php
/**
 * @package     Yajem.Administrator
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;

require_once ("attachment.php");

/**
 * @package     Yajem
 *
 * @since       1.1
 */
class YajemModelAttachments extends ListModel
{
	/**
	 * YajemModelAttachments constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @since 1.1
	 */
	public function __construct(array $config = array())
	{
		if (empty($config['filter_fields']))
		{
			// Add the standard ordering filtering fields whitelist.
			// Note to self. Must be exactly written as in the default.php. 'a.`field`' not the same as 'a.field'
			$config['filter_fields'] = array(
				'id', 'a.id',
				'eventId','a.eventId',
				'ationId','a.loacationId',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to receive all attachments for a given event
	 *
	 * @param int    $id   ID of the Event
	 *
	 * @param string $type 'location' or 'event'
	 *
	 * @return mixed Attachment for given item for given event
	 *
	 * @since 1.1
	 */
	public function getAttachments($id, $type)
	{
		$db = $this->getDbo();
		switch ((string)$type) {
			case "event":
				$this->setState('filter.eventId', $id);
				break;
			case "location":
				$this->setState('filter.locationId', $id);
				break;
			default:
				break;
		}
		$this->setState('filter.eventId', $id);
		$this->__state_set = true;
		$db->setQuery($this->getListQuery());

		$result = $db->loadObjectList();
		return $result;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   null $ordering  default=a.title
	 * @param   null $direction default=asc
	 *
	 * @return void
	 * @since 1.1
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = Factory::getApplication('administrator');

		$this->setState('filter.eventId', $app->getUserStateFromRequest($this->context . '.filter.eventId', 'filter_eventId'));
		$this->setState('filter.locationId', $app->getUserStateFromRequest($this->context . '.filter.locationId', 'filter_locationId'));

		$params = JComponentHelper::getParams('com_yajem');
		$this->setState('params', $params);

		parent::populateState('a.id', 'desc');
	}

	/**
	 *
	 * @return JDatabaseQuery
	 *
	 * @since 1.1
	 */
	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('a.id', 'a.eventId', 'a.locationId', 'a.file', 'a.title', 'a.description')));

		$query->from('#__yajem_attachments AS a');

		$query->select('e.title AS event');
		$query->join('LEFT', '#__yajem_events AS e ON e.id = a.eventId');

		$query->select('l.title AS location');
		$query->join('LEFT', '#__yajem_locations AS l ON l.id = a.locationId');

		$eventId = $this->getState('filter.eventId');

		if (is_numeric($eventId)) // Set Filter for State
		{
			$query->where('a.eventId = ' . (int) $eventId);
		}

		$filterLocation = $this->state->get('filter.locationId');

		if ($filterLocation)
		{
			$query->where("a.`locationId` = '" . $db->escape($filterLocation) . "'");
		}

		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * @param array  $attachments Array of Attachments
	 * @param string $type        'location' or 'event'
	 * @param int    $id          Id of location or event
	 *
	 *
	 * @since version
	 * @throws Exception
	 */
	public function saveAttachments($attachments, $type, $id) {
		for ($i = 0; $i < count($attachments); $i++)
		{
			if ($attachments[$i]['error'] == 0)
			{
				$filename   = File::makeSafe($attachments[$i]['name']);
				$src        = $attachments[$i]['tmp_name'];
				$dest       = JPATH_SITE . DIRECTORY_SEPARATOR . "media" . DIRECTORY_SEPARATOR . "com_yajem" .DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $filename;
				$url        = "media" .DIRECTORY_SEPARATOR . "com_yajem" .DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $filename;
				File::upload($src, $dest);
				$modelAttachment = new YajemModelAttachment();
				switch ((string)$type) {
					case "event":
						$data       = ['eventId' => $id, 'locationId' => null, 'file' => $url, 'title' => $filename, 'description' => null];
						$modelAttachment->save($data);
						Factory::getApplication()->enqueueMessage($filename . Text::_('COM_YAJEM_UPLOAD_SUCCESS'), 'message');
						break;
					case "location":
						$data       = ['eventId' => null, 'locationId' => $id, 'file' => $url, 'title' => $filename, 'description' => null];
						$modelAttachment->save($data);
						Factory::getApplication()->enqueueMessage($filename . Text::_('COM_YAJEM_UPLOAD_SUCCESS'), 'message');
						break;
					default:
						Factory::getApplication()->enqueueMessage($filename . Text::_('COM_YAJEM_UPLOAD_FAILED'), 'error');
						break;
				}
			}
		}
	}

}
