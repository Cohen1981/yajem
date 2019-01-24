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
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

// Require the helpers
require_once (JPATH_ADMINISTRATOR . '/components/com_yajem/helpers/yajem.php');
require_once (JPATH_ADMINISTRATOR . '/components/com_yajem/helpers/tableHelper.php');
require_once (JPATH_ADMINISTRATOR . '/components/com_yajem/helpers/defines.php');

$language = Factory::getLanguage();
$extension = 'com_yajem';
$language_tag = $language->getTag(); // loads the current language-tag
$base_dir = JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . $extension;
$language->load($extension, $base_dir, $language_tag, true);

$controller = BaseController::getInstance('yajem');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
