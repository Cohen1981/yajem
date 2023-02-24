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

/**
 * @ since      1.0.0
 * @package     Sda\Component\Sdajem\Administrator\Table
 *
 * Fields
 * @property  int       id
 * @property  int       $access
 * @property  string    alias
 * @property  Date      created
 * @property  int       created_by
 * @property  string    created_by_alias
 * @property  int       state
 * @property  int       ordering
 * @property  int       event_id
 * @property  int       users_user_id
 * @property  int       status
 */
class AttendingTable extends Table
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
		parent::__construct('#__sdajem_attendings', 'id', $db);
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
		$this->alias = 'attending';
		$this->alias = ApplicationHelper::stringURLSafe($this->alias);
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