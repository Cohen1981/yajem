<?php
/**
 * @package     Sda\Component\Sdajem\Site\View
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\View\Events;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;



\defined('_JEXEC') or die;

class HtmlView extends \Joomla\CMS\MVC\View\HtmlView
{
	/**
	 * The page parameters
	 *
	 * @var    \Joomla\Registry\Registry|null
	 * @since  __BUMP_VERSION__
	 */
	protected $params = null;

	/**
	 * The item model state
	 *
	 * @var    \Joomla\Registry\Registry
	 * @since  __BUMP_VERSION__
	 */
	protected $state;

	/**
	 * The item object details
	 *
	 * @var    \JObject
	 * @since  __BUMP_VERSION__
	 */
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

		$state = $this->state = $this->get('State');
		$this->params = $this->params = $state->get('params');

		return parent::display($tpl);
	}
}