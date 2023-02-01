<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * @since       __BUMP_VERSION__
 * @package     Sda\Component\Sdajem\Administrator\Model
 *
 * @property  int       id
 * @property  string    title
 * @property  string    alias
 * @property  string    description
 * @property  string    url
 * @property  int       catid
 * @property  string    language
 * @property  string    street
 * @property  string    postalCode
 * @property  string    city
 * @property  string    stateAddress
 * @property  string    country
 * @property  string    latlng
 */
class LocationModel extends AdminModel
{
	/**
	 * The type alias for this content type.
	 *
	 * @var    string
	 * @since  __BUMP_VERSION__
	 */
	public $typeAlias = 'com_sdajem.location';

	/**
	 * The context used for the associations table
	 *
	 * @var    string
	 * @since  __BUMP_VERSION__
	 */
	protected $associationsContext = 'com_sdajem.item';
	/**
	 * @param   array  $data
	 * @param   bool   $loadData
	 *
	 * @return Form|false
	 *
	 * @since __BUMP_VERSION__
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		try
		{
			$form = $this->loadForm($this->typeAlias, 'location', ['control' => 'jform', 'load_data' => $loadData]);
		}
		catch (\Exception $e)
		{
			return false;
		}
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function loadFormData()
	{
		$app = Factory::getApplication();

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_sdajem.edit.location.data', []);

		if (empty($data)) {
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('location.id') == 0) {
				$data->set('catid', $app->input->get('catid', $app->getUserState('com_sdajem.locations.filter.category_id'), 'int'));
			}
		}

		$this->preprocessData($this->typeAlias, $data);

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		// Load associated event items
		$assoc = Associations::isEnabled();

		if ($assoc) {
			$item->associations = [];

			if ($item->id != null) {
				$associations = Associations::getAssociations('com_sdajem', '#__sdajem_locations', 'com_sdajem.item', $item->id, 'id', null);

				foreach ($associations as $tag => $association) {
					$item->associations[$tag] = $association->id;
				}
			}
		}

		return $item;
	}

	/**
	 * Preprocess the form.
	 *
	 * @param   \JForm  $form   Form object.
	 * @param   object  $data   Data object.
	 * @param   string  $group  Group name.
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function preprocessForm(\JForm $form, $data, $group = 'content')
	{
		if (Associations::isEnabled()) {
			$languages = LanguageHelper::getContentLanguages(false, true, null, 'ordering', 'asc');

			if (count($languages) > 1) {
				$addform = new \SimpleXMLElement('<form />');
				$fields = $addform->addChild('fields');
				$fields->addAttribute('title', 'associations');
				$fieldset = $fields->addChild('fieldset');
				$fieldset->addAttribute('title', 'item_associations');

				foreach ($languages as $language) {
					$field = $fieldset->addChild('field');
					$field->addAttribute('title', $language->lang_code);
					#$field->addAttribute('type', 'modal_event');
					$field->addAttribute('language', $language->lang_code);
					$field->addAttribute('label', $language->title);
					$field->addAttribute('translate_label', 'false');
					$field->addAttribute('select', 'true');
					$field->addAttribute('new', 'true');
					$field->addAttribute('edit', 'true');
					$field->addAttribute('clear', 'true');
				}

				$form->load($addform, false);
			}
		}

		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   \Sda\Component\Sdajem\Administrator\Table\LocationTable  $table  The Table object
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function prepareTable($table)
	{
		$table->generateAlias();
	}
}