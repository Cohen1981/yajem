<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_sdajem
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sda\Component\Sdajem\Administrator\View\Events;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarFactoryInterface;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View class for a list of events.
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	protected $items;
	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function display($tpl = null): void
	{
		$this->items = $this->get('Items');

		if (!count($this->items) && $this->get('IsEmptyState')) {
			$this->setLayout('emptystate');
		}
		$this->addToolbar();

		HTMLHelper::_('formbehavior.chosen', 'select');

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		$canDo = ContentHelper::getActions('com_sdajem');
		// Get the toolbar object instance
		#$toolbar = Factory::getContainer()->get('Joomla\CMS\Toolbar\ToolbarFactoryInterface');

		$toolbar = Toolbar::getInstance('toolbar');
		ToolbarHelper::title(Text::_('COM_SDAJEM_MANAGER_EVENTS'), 'address event');

		if ($canDo->get('core.create'))
		{
			$toolbar->addNew('event.add');
		}
		if ($canDo->get('core.options'))
		{
			$toolbar->preferences('com_sdajem');
		}
	}
}