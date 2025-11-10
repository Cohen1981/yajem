<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
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
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Sda\Component\Sdajem\Administrator\Model\LocationModel;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @since       version
 * @package     Sda\Component\Sdajem\Administrator\Table
 *
 * @property  int   user_id
 */
class FittingTable extends Table
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
		$app = Factory::getApplication();
		try {
			parent::check();
		} catch (Exception $e) {
			$app->enqueueMessage($e->getMessage(),'error');
			return false;
		}
		// Check the publish down date is not earlier than publish up.
		if ($this->publish_down > $this->getDatabase()->getNullDate() && $this->publish_down < $this->publish_up) {
			$app->enqueueMessage(Text::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'),'warning');
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
		if (!$this->created_by) {
			$this->created_by = Factory::getApplication()->getIdentity()->id;
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