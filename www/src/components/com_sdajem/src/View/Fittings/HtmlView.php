<?php
/**
 * @package     Sda\Component\Sdajem\Site\View\Attendings
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\View\Fittings;

defined('_JEXEC') or die();

use Joomla\CMS\Uri\Uri;

class HtmlView extends \Joomla\CMS\MVC\View\HtmlView
{
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
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->state = $this->get('State');
		$this->return_page = base64_encode(Uri::getInstance());

		return parent::display($tpl);
	}

}