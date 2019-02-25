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

$language = Factory::getLanguage();
$extension = 'com_profileYajem';
$languageTag = $language->getTag();
$baseDir = JPATH_SITE . "/administrator/components/" . $extension;
$language->load($extension, $baseDir, $languageTag, true);

$controller = BaseController::getInstance('profileYajem');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
