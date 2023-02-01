<?php

namespace Sda\Component\Sdajem\Site\View\Locations;

\defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 * @since       __BUMP_VERSION__
 */

class HtmlView extends BaseHtmlView
{
	/**
	 * An array of items
	 *
	 * @var  array
	 * @since __BUMP_VERSION__
	 */
	protected $items;

	/**
	 * The model state
	 *
	 * @var  \JObject
	 * @since __BUMP_VERSION__
	 */
	protected $state;

	/**
	 * Form object for search filters
	 *
	 * @var  \JForm
	 * @since __BUMP_VERSION__
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var  array
	 * @since __BUMP_VERSION__
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
	 * @since   __BUMP_VERSION__
	 */
	public function display($tpl = null): void
	{
		$this->items = $this->get('Items');

		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->state = $this->get('State');

		// Check for errors.
		#if (count($errors = $this->get('Errors'))) {
		#	throw new GenericDataException(implode("\n", $errors), 500);
		#}

		// Preprocess the list of items to find ordering divisions.
		// TODO: Complete the ordering stuff with nested sets
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

			// One last changes needed is to change the category filter to just show categories with All language or with the forced language.
			$this->filterForm->setFieldAttribute('category_id', 'language', '*,' . $forcedLanguage, 'filter');
		}

		parent::display($tpl);
	}
}