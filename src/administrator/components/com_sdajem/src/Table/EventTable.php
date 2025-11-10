<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @package     Sda\Component\Sdajem\Administrator\Table
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Table;

use Exception;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\Registry\Registry;
use Sda\Component\Sdajem\Site\Enums\EventStatusEnum;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @since      1.0.0
 * @package     Sda\Component\Sdajem\Administrator\Table
 *
 * @property  int       id
 * @property  int       $access
 * @property  string    alias
 * @property  Date      created
 * @property  int       created_by
 * @property  string    created_by_alias
 * @property  int       checked_out
 * @property  Date      checked_out_time
 * @property  int       published
 * @property  Date      publish_up
 * @property  Date      publish_down
 * @property  int       state
 * @property  int       ordering
 * @property  string    language
 * @property  string    title
 * @property  string    description
 * @property  string    url
 * @property  string    image
 * @property  int       sdajem_location_id fk to locations table
 * @property  int       hostId
 * @property  int       organizerId
 * @property  Date      startDateTime
 * @property  Date      endDateTime
 * @property  int       allDayEvent
 * @property  int       eventStatus
 * @property  int       catid
 * @property  string    svg
 * @property  Date      registerUntil
 */
class EventTable extends Table
{
	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  Database connector object
	 *
	 * @since   1.0.0
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_sdajem';
		parent::__construct('#__sdajem_events', 'id', $db);
	}

	/**
	 * Generate a valid alias from title / date.
	 * Remains public to be able to check for duplicated alias before saving
	 *
	 * @return  string
	 *
	 * @since   1.0.0
	 */
	public function generateAlias()
	{
		if (empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->language);
		if (trim(str_replace('-', '', $this->alias)) == '') {
			$this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
		}
		return $this->alias;
	}

	public function check()
	{
		$app = Factory::getApplication();

		try {
			parent::check();
		} catch (Exception $e) {
			$app->enqueueMessage($e->getMessage(),'error');;
			return false;
		}
		// Check the publish down date is not earlier than publish up.
		if ($this->publish_down > $this->getDatabase()->getNullDate() && $this->publish_down < $this->publish_up) {
			$app->enqueueMessage(Text::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'),'warning');
			return false;
		}
		if (!$this->created_by) {
			$this->created_by = Factory::getApplication()->getIdentity()->id;
		}
		if (!$this->created) {
			$this->created = Date::getInstance()->toSql();
		}
		// Set publish_up, publish_down to null if not set
		if (!$this->publish_up) {
			$this->publish_up = null;
		}
		if (!$this->publish_down) {
			$this->publish_down = null;
		}
		if (!$this->published) {
			$this->published = 1;
		}
		if (!$this->hostId) {
			$this->hostId = null;
		}
		if (!$this->organizerId) {
			$this->organizerId = null;
		}
		if (!$this->eventStatus) {
			$this->eventStatus = EventStatusEnum::OPEN->value;
			$this->access = 1;
		}
		if ($this->eventStatus == EventStatusEnum::PLANING->value) {
			$this->access =2;
		}
		if (!$this->registerUntil) {
			$this->registerUntil = null;
		}
		//if (!$this->checked_out) {
		$this->checked_out = null;
		$this->checked_out_time = null;
		//}
		return true;
	}

	/**
	 * @param   bool  $updateNulls
	 *
	 * @return bool
	 *
	 * @since 1.0.2
	 */
	public function store($updateNulls = true)
	{
		// Transform the params field
		if (is_array($this->params)) {
			$registry = new Registry($this->params);
			$this->params = (string) $registry;
		}

		return parent::store($updateNulls);
	}
}