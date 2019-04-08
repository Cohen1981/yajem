<?php
/**
 * @package     Yajem.Administrator
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

HtmlHelper::addIncludePath(JPATH_COMPONENT . '/helpers/');
JLoader::register('YajemHelperAdmin', JPATH_COMPONENT . '/helpers/yajem.php');

/**
 * @package     Yajem
 *
 * @since       version
 */

class YajemViewLocations extends HtmlView
{
	/**
	 * @var locations
	 * @since 1.0
	 */
	protected $items;

	/**
	 * @var pagination
	 * @since 1.0
	 */
	protected $pagination;

	/**
	 * @var state
	 * @since 1.0
	 */
	protected $state;

	/**
	 * @param   null $tpl Template to render
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	public function display($tpl = null)
	{
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');

		// Get the filter form
		$this->filterForm = $this->get('FilterForm');

		// Get active filters
		$this->activeFilters = $this->get('ActiveFilters');

		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		YajemHelperAdmin::addSubmenu('locations');

		$user = Factory::getUser();

		return parent::display($tpl);
	}

	/**
	 * Get sortable Fields
	 *
	 * @return array Array of sortable Fields
	 *
	 * @since 1.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering'    => JText::_('JGRID_HEADING_ORDERING'),
			'a.id'          => JText::_('JGRID_HEADING_ID'),
			'a.published'   => JText::_('JSTATUS'),
			'a.title'       => JText::_('COM_YAJEM_TITLE_LOCATIONS'),
			'a.catid'       => JText::_('COM_YAJEM_TITLE_LOCATIONS_CATID'),
			'contact'       => JText::_('COM_YAJEM_TITLE_LOCATIONS_CONTACT')
		);
	}
}
