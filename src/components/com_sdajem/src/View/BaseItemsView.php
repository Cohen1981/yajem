<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @package     Sda\Component\Sdajem\Site\View
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\View;

use Exception;
use JObject;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

class BaseItemsView extends HtmlView
{
	/**
	 * The page parameters
	 *
	 * @var    Registry|null
	 * @since  1.0.0
	 */
	protected $params = null;

	/**
	 * The item model state
	 *
	 * @var    Registry
	 * @since  1.0.0
	 */
	protected $state;

	/**
	 * The item object details
	 *
	 * @var    \stdClass
	 * @since  1.0.0
	 */
	protected $items;
	/**
	 * @param   null  $tpl
	 *
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	public function display($tpl = null)
	{
		$model = $this->getModel();
		$this->items = $model->getItems();
		$this->pagination = $model->getPagination();
		$this->filterForm = $model->getFilterForm();
		$this->activeFilters = $model->getActiveFilters();
		$this->state = $model->getState();
		$this->return_page = base64_encode(Uri::getInstance());

		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item) {
			$item->order_up = true;
			$item->order_dn = true;
		}

		parent::display($tpl);
	}
}