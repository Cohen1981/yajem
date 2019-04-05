<?php
/**
 * @package     Sda\Jem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Site\Model;

use Sda\Jem\Admin\Model\Location as AdminLocation;

/**
 * @package     Sda\Jem\Site\Model
 *
 * @since       0.0.1
 */
class Location extends AdminLocation
{
	/**
	 * Set the enabled state, which is not accessible in Frontend.
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	protected function onBeforeSave()
	{
		parent::onBeforeSave();
		$this->enabled = 1;
	}
}