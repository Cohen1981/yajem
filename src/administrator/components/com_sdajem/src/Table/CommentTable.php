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
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

/**
 * @since      1.0.0
 * @package     Sda\Component\Sdajem\Administrator\Table
 *
 * Fields
 * @property  int       id
 * @property  int       users_user_id
 * @property  int       sdajem_event_id
 * @property  string    comment
 * @property  \DateTime timestamp
 * @property  string    commentReadBy
 *
 */
class CommentTable extends Table
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
		parent::__construct('#__sdajem_comments', 'id', $db);
	}

	public function check()
	{
		try {
			parent::check();
		} catch (\Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		if (!$this->users_user_id) {
			$this->users_user_id = Factory::getApplication()->getIdentity()->id;
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