<?php

/** @var \Sda\Jem\Site\View\Event\Html $this */

use Joomla\CMS\Document\Document;
use Joomla\CMS\Document\JsonDocument as JDocumentJSON;

/** @var \Sda\Jem\Site\Model\Event $event */

/** @var \Sda\Jem\Site\Model\Event $model */
$model = $this->getModel();

// Show only enabled events sorted ascending
$items = $model->get()->toArray();

$document = $this->container->platform->getDocument();

/** @var JDocumentJSON $document */
if ($document instanceof Document)
{
	$document->setMimeEncoding('application/json');
	$document->setName('events');
}

echo json_encode($items,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);