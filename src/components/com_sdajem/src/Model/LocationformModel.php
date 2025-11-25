<?php


/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Table\Table;
use Sda\Component\Sdajem\Administrator\Library\Item\Location;

class LocationformModel extends \Sda\Component\Sdajem\Administrator\Model\LocationModel
{
	/**
	 * Model typeAlias string. Used for version history.
	 *
	 * @var  string
	 * @since  __DEPLOY_VERSION__
	 */
	public $typeAlias = 'com_sdajem.location';
	/**
	 * Name of the form
	 *
	 * @var string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $formName = 'form';

	/**
	 * Method to get the row form.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 * @param   array    $data      Data for the form.
	 *
	 * @return  Form|boolean  A \JForm object on success, false on failure
	 */
	public function getForm($data = [], $loadData = true)
	{
		$form = parent::getForm($data, $loadData);

		return $form;
	}

	/**
	 * Method to get location data.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   integer  $itemId  The id of the location.
	 *
	 * @return  mixed  Location item data object on success, false on failure.
	 * @throws  Exception
	 */
	public function getItem($itemId = null)
	{
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('locationform.id');
		// Get a row instance.
		$table = $this->getTable();
		// Attempt to load the row.
		try
		{
			if (!$table->load($itemId))
			{
				return false;
			}
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage());

			return false;
		}
		$properties = $table->getProperties();

		//return ArrayHelper::toObject($properties, stdClass::class);
		return Location::createFromArray($properties);
		//return $table;
	}

	/**
	 * Allows preprocessing of the JForm object.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   array   $data   The data to be merged into the form object
	 * @param   string  $group  The plugin group to be executed
	 * @param   Form    $form   The form object
	 *
	 * @return void
	 * @throws Exception
	 */
	protected function preprocessForm(Form $form, $data, $group = 'location')
	{
		if (!Multilanguage::isEnabled())
		{
			$form->setFieldAttribute('language', 'type', 'hidden');
			$form->setFieldAttribute('language', 'default', '*');
		}
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 * @param   string  $name     The table name. Optional.
	 *
	 * @return  Table  A Table object
	 * @throws  Exception
	 */
	public function getTable($name = 'Location', $prefix = 'Administrator', $options = [])
	{
		return parent::getTable($name, $prefix, $options);
	}

	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page', ''));
	}
}
