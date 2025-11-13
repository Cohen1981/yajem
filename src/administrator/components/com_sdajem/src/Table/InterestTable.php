<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

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
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

defined('_JEXEC') or die();

/**
 * @since      1.0.0
 * @package     Sda\Component\Sdajem\Administrator\Table
 *
 * Fields
 * @property  int       id
 * @property  int       $access
 * @property  string    alias
 * @property  int       state
 * @property  int       ordering
 * @property  int       event_id
 * @property  int       users_user_id
 * @property  int       status
 * @property  string    comment
 */
class InterestTable extends Table
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
		parent::__construct('#__sdajem_interest', 'id', $db);
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
		$this->alias = 'interest';
		$this->alias = ApplicationHelper::stringURLSafe($this->alias);
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
			$app->enqueueMessage($e->getMessage(), 'error');
			return false;
		}

		if (!$this->users_user_id) {
			$this->users_user_id = $app->getIdentity()->id;
		}
		return true;
	}

	/**
	 * @param   bool  $updateNulls
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function store($updateNulls = true)
	{
		return parent::store($updateNulls);
	}
}