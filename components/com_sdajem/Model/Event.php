<?php
/**
 * @package     Sda\Jem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Site\Model;

use Sda\Jem\Admin\Model\Event as AdminEvent;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

/**
 * @package     Sda\Jem\Site\Model
 *
 * @since       0.0.1
 */
class Event extends AdminEvent
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

		if (!$this->input->get('task') == 'archiveEvent')
		{
			$this->enabled = 1;
		}
	}
}
