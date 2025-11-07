<?php
/**
 * @package     Sda\Component\Sdajem\Site\View
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\View\Events;

defined('_JEXEC') or die();

use Joomla\CMS\Uri\Uri;
use Sda\Component\Sdajem\Site\Model\EventsModel;

class HtmlView extends \Joomla\CMS\MVC\View\HtmlView {
	/**
	 * The page parameters
	 *
	 * @var    \Joomla\Registry\Registry|null
	 * @since  1.0.0
	 */
	protected $params = null;

	/**
	 * The item model state
	 *
	 * @var    \Joomla\Registry\Registry
	 * @since  1.0.0
	 */
	protected $state;

	/**
	 * The item object details
	 *
	 * @var    \JObject
	 * @since  1.0.0
	 */
	protected $items;
	/**
	 * @param   null  $tpl
	 *
	 *
	 * @throws \Exception
	 * @since 1.0.0
	 */
	public function display($tpl = null)
	{
		/** @var EventsModel $model */
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

		return parent::display($tpl);
	}
}