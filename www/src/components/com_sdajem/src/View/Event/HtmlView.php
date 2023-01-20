<?php
/**
 * @package     Sda\Component\Sdajem\Site\View\Event
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\View\Event;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * @package     Sda\Component\Sdajem\Site\View\Event
 *
 * @since       __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The item object details
	 *
	 * @var    \JObject
	 * @since  __BUMP_VERSION__
	 */
	protected $item;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$this->item = $this->get('Item');

		return parent::display($tpl);
	}
}