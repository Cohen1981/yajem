<?php
/**
 * @package     Sda\Jem
 *
 * @copyright   2019 Alexander Bahlo
 * @license     A "Slug" license name e.g. GPL2
 */

use FOF30\Container\Container;

defined('_JEXEC') or die();

if (!defined('FOF30_INCLUDED') && !@include_once JPATH_LIBRARIES . '/fof30/include.php')
{
	throw new RuntimeException('FOF 3.0 is not installed', 500);
}

Container::getInstance('com_sdajem')->dispatcher->dispatch();
