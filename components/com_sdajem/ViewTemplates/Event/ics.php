<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Factory;

/** @var \Sda\Jem\Site\View\Event\Html $this */
/** @var \Sda\Jem\Site\Model\Event $event */
$event = $this->getModel('Event');

$ics = $event->makeIcs();
$document = Factory::getDocument();
$document->setMimeEncoding('text/calendar; charset=utf-8');
Factory::getApplication()
	->setHeader(
		'Content-disposition',
		'attachment; filename="invite.ics"; creation-date="' . Factory::getDate()->toRFC822() . '"',
		true
	)
	->setHeader('Content-Type', 'text/calendar; charset=utf-8', true)
	->setHeader('Content-Length', strlen($ics), true)
	->setHeader('Connection', 'close', true);
echo $ics;
