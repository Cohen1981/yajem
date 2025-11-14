<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @package     Sda\Component\Sdajem\Administrator\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Model;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * @since       1.1.1
 * @package     Sda\Component\Sdajem\Administrator\Model
 *
 * @property int    id
 * @property string title
 * @property string description
 * @property double length
 * @property double width
 * @property int    standard
 * @property int    needSpace
 * @property string image
 */
class FittingModel extends AdminModel
{

	/**
	 * The type alias for this content type.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	public $typeAlias = 'com_sdajem.fitting';

	/**
	 * @param   array  $data
	 * @param   bool   $loadData
	 *
	 * @return Form|false
	 *
	 * @since 1.0.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		try
		{
			$form = $this->loadForm($this->typeAlias, 'fitting', ['control' => 'jform', 'load_data' => $loadData]);
		}
		catch (Exception $e)
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
	 * @throws Exception
	 * @since   1.0.0
	 */
	protected function loadFormData()
	{
		$app = Factory::getApplication();

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_sdajem.edit.fitting.data', []);

		if (empty($data)) {
			$data = $this->getItem();
		}

		$this->preprocessData($this->typeAlias, $data);

		return $data;
	}

}