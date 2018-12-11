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
	 * @since version
	 */
	protected $items;

	/**
	 * @var   $pagination
	 * @since version
	 */
	protected $pagination;

	/**
	 * @var   $state
	 * @since version
	 */
	protected $state;

	/**
	 * @var   $params
	 * @since version
	 */
	protected $params;

	/**
	 * @param   null $tpl Template to load
	 *
	 * @return mixed|void
	 *
	 * @since version
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

		parent::display($tpl);
	}
}