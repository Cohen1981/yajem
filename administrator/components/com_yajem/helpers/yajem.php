<?php
/**
 * @package    yajem
 *
 * @author     abahl <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

/**
 * Yajem helper.
 *
 * @package     A package name
 * @since       1.0
 */
class YajemHelper
{
	/**
	 * Render submenu.
	 *
	 * @param   string  $vName  The name of the current view.
	 *
	 * @return  void.
	 *
	 * @since   1.0
	 */
	public function addSubmenu($vName)
	{
		#JHtmlSidebar::addEntry(Text::_('COM_YAJEM'), 'index.php?option=com_yajem&view=yajem', $vName == 'yajem');
		JHtmlSidebar::addEntry(
			JText::_('COM_YAJEM_SUBMENU_EVENTS'),
			'index.php?option=com_yajem&view=events',
			$vName == 'events'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_YAJEM_SUBMENU_LOCATIONS'),
			'index.php?option=com_yajem&view=locations',
			$vName == 'locations'
		);

		JHtmlSidebar::addEntry(
			JText::_('JCATEGORIES') . ' (Yajem)',
			"index.php?option=com_categories&extension=com_yajem&view=categories",
			$vName == 'categories'
		);
	}
}
