<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_sdajem
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Language\Text;

\defined('_JEXEC') or die;

/* @var \Sda\Component\Sdajem\Site\Model\EventModel $event */
?>

<?php
echo Text::_('COM_SDAJEM_NAME') . $this->item->title;

echo $this->item->event->afterDisplayTitle;
echo $this->item->event->beforeDisplayContent;
echo $this->item->event->afterDisplayContent;