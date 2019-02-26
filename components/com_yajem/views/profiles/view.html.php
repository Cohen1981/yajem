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
use Yajem\Helpers\YajemParams;
use Joomla\CMS\Factory;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemViewProfiles extends HtmlView
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
		$this->params     = new YajemParams;

		$document = Factory::getDocument();
		$document->addStyleSheet(JUri::root() . 'media/com_yajem/css/style.css');
		$document->setHtml5(true);

		parent::display($tpl);
	}
}