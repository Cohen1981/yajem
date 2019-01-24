<?php
/**
 * @package    yajem
 *
 * @author     abahl <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

// Require the helpers
require_once (JPATH_ADMINISTRATOR . '/components/com_yajem/helpers/yajem.php');
require_once (JPATH_ADMINISTRATOR . '/components/com_yajem/helpers/tableHelper.php');
require_once (JPATH_ADMINISTRATOR . '/components/com_yajem/helpers/defines.php');

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_yajem'))
{
	throw new InvalidArgumentException(Text::_('JERROR_ALERTNOAUTHOR'), 404);
}

// Execute the task
$controller = BaseController::getInstance('yajem');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
