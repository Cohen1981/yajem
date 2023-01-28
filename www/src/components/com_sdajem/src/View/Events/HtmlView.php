<?php
/**
 * @package     Sda\Component\Sdajem\Site\View
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\View\Events;

use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Categories\CategoryFactory;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\Helpers\Category;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Uri\Uri;


\defined('_JEXEC') or die;

class HtmlView extends \Joomla\CMS\MVC\View\HtmlView
{
	protected $items;
	/**
	 * @param   null  $tpl
	 *
	 *
	 * @throws \Exception
	 * @since version
	 */
	public function display($tpl = null)
	{

		$document = Factory::getDocument();
		$document->addStyleSheet(Uri::root() . '/media/com_sdajem/css/toolbar.css');

		$this->items = $this->get('Items');

		return parent::display($tpl);
	}

	protected function renderToolbar(): string {
		$this->canDo = ContentHelper::getActions('com_sdajem');
		$title = Text::_('COM_SDAJEM_EVENTS_TITLE');

		ToolbarHelper::title($title);
		if ($this->canDo->get('core.create'))
		{
			ToolbarHelper::addNew('add', 'COM_SDAJEM_BUTTON_CODE_NEW');
		}
		ToolbarHelper::back();

		return Toolbar::getInstance()->render();
	}
}