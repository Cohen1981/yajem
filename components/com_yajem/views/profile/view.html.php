<?php
/**
 * @package     Yajem.Site
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Yajem\User\YajemUserProfile;
use Joomla\CMS\Factory;

/**
 * @package     Yajem
 *
 * @since       version
 */
class YajemViewProfile extends HtmlView
{
	/**
	 * @var state
	 * @since 1.0
	 */
	protected $state;

	/**
	 * @var YajemUserProfile $profile
	 * @since 1.0
	 */
	protected $profile;

	/**
	 * @param   null $tpl Template to load
	 *
	 * @return mixed|void
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->profile = $this->get('Data', 'Profile');

		$document = Factory::getDocument();
		$document->addStyleSheet(JUri::root() . 'media/com_yajem/css/style.css');
		$document->addScript(JUri::root() . 'media/com_yajem/js/ical.js');
		$document->setHtml5(true);

		parent::display($tpl);
	}
}