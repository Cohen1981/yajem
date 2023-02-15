<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Table
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Table;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Sda\Component\Sdajem\Administrator\Model\LocationModel;

/**
 * @ since      1.0.0
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
 * @property  string    street
 * @property  string    postalCode
 * @property  string    city
 * @property  string    stateAddress
 * @property  string    country
 * @property  string    latlng
 * @property  int       contactId
 * @property  string    image
 * @property  int       catid
 */
class LocationTable extends Table
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
		parent::__construct('#__sdajem_locations', 'id', $db);
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
		/* @var LocationModel $this */
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
		try {
			parent::check();
		} catch (\Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
		// Check the publish down date is not earlier than publish up.
		if ($this->publish_down > $this->_db->getNullDate() && $this->publish_down < $this->publish_up) {
			$this->setError(Text::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));
			return false;
		}
		// Set publish_up, publish_down to null if not set
		if (!$this->publish_up) {
			$this->publish_up = null;
		}
		if (!$this->publish_down) {
			$this->publish_down = null;
		}
		if (!$this->checked_out) {
			$this->checked_out = null;
			$this->checked_out_time = null;
		}
		return true;
	}

	/**
	 * @param   bool  $updateNulls
	 *
	 * @return bool
	 *
	 * @ since 1.0.0
	 */
	public function store($updateNulls = true)
	{
		return parent::store($updateNulls);
	}
}