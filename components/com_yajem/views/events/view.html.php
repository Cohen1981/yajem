<?php
/**
 * @package     Yajem.Site
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemViewEvents extends HtmlView
{
	/**
	 * @var   $items
	 * @since 1.0
	 */
	protected $items;

	/**
	 * @var   $pagination
	 * @since 1.0
	 */
	protected $pagination;

	/**
	 * @var   $state
	 * @since 1.0
	 */
	protected $state;

	/**
	 * @var   $params
	 * @since 1.0
	 */
	protected $params;

	/**
	 * @param   null $tpl Template to load
	 *
	 * @return mixed|void
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$this->state      = $this->get('State');
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->params     = $app->getParams('com_yajem');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		YajemHelperAdmin::setDocument();

		parent::display($tpl);
	}
}