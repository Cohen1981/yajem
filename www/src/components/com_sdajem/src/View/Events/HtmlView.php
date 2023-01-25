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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

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
		$this->items = $this->get('Items');

		$categories = Categories::getInstance('sdajem.event');
#		$this->category = $categories->get($this->getState('category.id', 'root'));

		return parent::display($tpl);
	}
}