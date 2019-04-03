<?php
use FOF30\Container\Container;
use Joomla\CMS\Factory;

defined('_JEXEC') or die();

if (!defined('FOF30_INCLUDED') && !@include_once JPATH_LIBRARIES . '/fof30/include.php')
{
	throw new RuntimeException('FOF 3.0 is not installed', 500);
}

$language    = Factory::getLanguage();
$extension   = 'com_sdajem';
$languageTag = $language->getTag();
$baseDir     = JPATH_ADMINISTRATOR . "/components/" . $extension;
$language->load($extension, $baseDir, $languageTag, true);

Container::getInstance('com_sdajem')->dispatcher->dispatch();
