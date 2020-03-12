<?php
/**
 * @package     Sda\Jem\Site\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Site\Controller;

use FOF30\Controller\DataController;
use Sda\Jem\Admin\Helper\RefererHelper;

/**
 * @package     Sda\Jem\Site\Controller
 *
 * @since       0.0.1
 */
class Category extends DataController
{
	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	protected function onBeforeAdd()
	{
		if ($_REQUEST['type'])
		{
			$this->defaultsForAdd['type'] = $_REQUEST['type'];
		}
	}
	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onAfterSave() : void
	{
		$this->setRedirect(RefererHelper::getReferer());
	}

	/**
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onAfterCancel()
	{
		$this->setRedirect(RefererHelper::getReferer());
	}
}