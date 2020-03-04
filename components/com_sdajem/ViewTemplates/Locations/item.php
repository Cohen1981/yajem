<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

/** @var \Sda\Jem\Site\View\Location\Html $this */

try
{
	echo $this->loadAnyTemplate('site:com_sdajem/Locations/location');
}
catch (Exception $e)
{
}
