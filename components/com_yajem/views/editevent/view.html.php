<?php
/**
 * @package     Yajem.Administrator
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemViewEditevent extends HtmlView
{
	/**
	 * @var state
	 * @since 1.0
	 */
	protected $state;

	/**
	 * @var event
	 * @since 1.0
	 */
	protected $event;

	/**
	 * @var form
	 * @since 1.0
	 */
	protected $form;

	/**
	 * @param   null $tpl Template to be used
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');

		// Note to self: Property always Item.
		$this->event = $this->get('Item');
		$this->form = $this->get('Form');

		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		return parent::display($tpl);
	}

}