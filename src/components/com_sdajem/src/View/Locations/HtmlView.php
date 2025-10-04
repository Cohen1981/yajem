<?php

namespace Sda\Component\Sdajem\Site\View\Locations;

\defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Uri\Uri;

/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 * @since       1.0.0
 */

class HtmlView extends BaseHtmlView
{
	/**
	 * An array of items
	 *
	 * @var  array
	 * @since 1.0.0
	 */
	protected $items;

	/**
	 * The model state
	 *
	 * @var  \JObject
	 * @since 1.0.0
	 */
	protected $state;

	/**
	 * Form object for search filters
	 *
	 * @var  \JForm
	 * @since 1.0.0
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var  array
	 * @since 1.0.0
	 */
	public $activeFilters;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 * @since   1.0.0
	 */
	public function display($tpl = null): void
	{
		$this->items = $this->get('Items');

		$this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->state = $this->get('State');
		$this->return_page = base64_encode(Uri::getInstance());

		// Check for errors.
		#if (count($errors = $this->get('Errors'))) {
		#	throw new GenericDataException(implode("\n", $errors), 500);
		#}

		// Preprocess the list of items to find ordering divisions.
		#foreach ($this->items as &$item) {
		#	$item->order_up = true;
		#	$item->order_dn = true;
		#}

		#if (!count($this->items) && $this->get('IsEmptyState')) {
		#	$this->setLayout('emptystate');
		#}

		// In article associations modal we need to remove language filter if forcing a language.
		// We also need to change the category filter to show show categories with All or the forced language.
		if ($forcedLanguage = Factory::getApplication()->input->get('forcedLanguage', '', 'CMD')) {
			// If the language is forced we can't allow to select the language, so transform the language selector filter into a hidden field.
			$languageXml = new \SimpleXMLElement('<field name="language" type="hidden" default="' . $forcedLanguage . '" />');
			$this->filterForm->setField($languageXml, 'filter', true);

			// Also, unset the active language filter so the search tools is not open by default with this filter.
			unset($this->activeFilters['language']);
		}

		parent::display($tpl);
	}
}