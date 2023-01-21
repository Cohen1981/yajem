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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * @package     Sda\Component\Sdajem\Administrator\Model
 *
 * @since       __BUMP_VERSION__
 *
 * Fields:
 *
 * @property   int			$id
 * @property   string		$title
 * @property   string		$alias
 * @property   int          $access
 */
class EventModel extends AdminModel
{
	/**
	 * The type alias for this content type.
	 *
	 * @var    string
	 * @since  __BUMP_VERSION__
	 */
	public $typeAlias = 'com_sdajem.event';

	protected $associationsContext = 'com_sdajem.item';

	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  \JForm|boolean  A \JForm object on success, false on failure
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function getForm($data = [], $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm($this->typeAlias, 'event', ['control' => 'jform', 'load_data' => $loadData]);
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
		$data = $app->getUserState('com_sdajem.edit.event.data', []);
		if (empty($data)) {
			$data = $this->getItem();
			// Prime some default values.
			if ($this->getState('event.id') == 0) {
				$data->set('catid', $app->input->get('catid', $app->getUserState('com_sdajem.events.filter.category_id'), 'int'));
			}
		}
		$this->preprocessData($this->typeAlias, $data);

		return $data;
	}

	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);
		// Load associated foo items
		$assoc = Associations::isEnabled();
		if ($assoc) {
			$item->associations = [];
			if ($item->id != null) {
				$associations = Associations::getAssociations('com_sdajem', '#__sda_events', 'com_sdajem.item', $item->id, 'id', null);
				foreach ($associations as $tag => $association) {
					$item->associations[$tag] = $association->id;
				}
			}
		}
		return $item;
	}

	protected function preprocessForm(\JForm $form, $data, $group = 'content')
	{
		if (Associations::isEnabled()) {
			$languages = LanguageHelper::getContentLanguages(false, true, null, 'ordering', 'asc');
			if (count($languages) > 1) {
				$addform = new \SimpleXMLElement('<form />');
				$fields = $addform->addChild('fields');
				$fields->addAttribute('name', 'associations');
				$fieldset = $fields->addChild('fieldset');
				$fieldset->addAttribute('name', 'item_associations');
				foreach ($languages as $language) {
					$field = $fieldset->addChild('field');
					$field->addAttribute('name', $language->lang_code);
					$field->addAttribute('type', 'modal_foo');
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
	 * @param   \Joomla\CMS\Table\Table  $table  The Table object
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