<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Table
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Table;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseDriver;
use Sda\Component\Sdajem\Administrator\Model\LocationModel;

/**
 * @since       version
 * @package     Sda\Component\Sdajem\Administrator\Table
 *
 * @property  int   user_id
 */
class FittingTable extends \Joomla\CMS\Table\Table
{
	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  Database connector object
	 *
	 * @since   1.0.0
	 *
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_sdajem';
		parent::__construct('#__sdajem_fittings', 'id', $db);
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
		if (!$this->published) {
			$this->published = 1;
		}
		if (!$this->user_id) {
			$this->user_id = Factory::getApplication()->getIdentity()->id;
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