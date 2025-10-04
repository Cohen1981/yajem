<?php
/**
 * @package     Sda\Component\Sdajem\Site\View
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\View\Fittingform;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Uri\Uri;

/**
 * HTML Location View class
 *
 * @since  __DEPLOY_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * @var    \Joomla\CMS\Form\Form
	 * @since  __DEPLOY_VERSION__
	 */
	protected $form;
	/**
	 * @var    mixed | object
	 * @since  __DEPLOY_VERSION__
	 */
	protected $item;
	/**
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $return_page;
	/**
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $pageclass_sfx;
	/**
	 * @var    \Joomla\Registry\Registry
	 * @since  __DEPLOY_VERSION__
	 */
	protected $state;
	/**
	 * @var    \Joomla\Registry\Registry
	 * @since  __DEPLOY_VERSION__
	 */
	protected $params;
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed|void  A string if successful, otherwise an Error object.
	 *
	 * @throws Exception
	 * @since  __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		$user = Factory::getApplication()->getIdentity();
		$app  = Factory::getApplication();
		// Get model data.
		$this->state = $this->get('State');
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');
		$this->return_page = $this->get('ReturnPage');
		if (empty($this->item->id))
		{
			$authorised = $user->authorise('core.create', 'com_sdajem');
		} else {
			$authorised = $user->authorise('core.edit', 'com_sdajem');
			if ($authorised !== true) {
				$authorised = $user->id == $this->item->user_id;
			}
		}

		if ($authorised !== true) {
			$app->redirect('index.php?option=com_users&view=login');
		}
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			$app->enqueueMessage(implode("\n", $errors), 'error');
			return false;
		}
		// Create a shortcut to the parameters.
		$this->params = $this->state->params;
		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

		$this->return_page_edit = base64_encode(Uri::getInstance());

		$this->_prepareDocument();

		parent::display($tpl);
	}
	/**
	 * Prepares the document
	 *
	 * @return  void
	 *
	 * @throws Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function _prepareDocument()
	{
		$app   = Factory::getApplication();
		$menus = $app->getMenu();
		$title = null;
		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', Text::_('COM_SDAJEM_FORM_EDIT_FITTING'));
		}
		$title = $this->params->def('page_title', Text::_('COM_SDAJEM_FORM_EDIT_FITTING'));
		if ($app->get('sitename_pagetitles', 0) == 1) {
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		} else if ($app->get('sitename_pagetitles', 0) == 2) {
			$title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}
		$this->getDocument()->setTitle($title);
		$pathway = $app->getPathWay();
		$pathway->addItem($title, '');
	}
}
