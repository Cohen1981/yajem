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

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * View class for a list of events.
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
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
		parent::display($tpl);
	}
}