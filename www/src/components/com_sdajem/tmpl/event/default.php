<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_sdajem
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

/* @var \Sda\Component\Sdajem\Site\Model\EventModel $event */
?>
<?php
$event = $this->item;
echo $event->title;