<?php

use Joomla\CMS\Document\Document;
use Joomla\CMS\Document\JsonDocument as JDocumentJSON;

$model = $this->getModel();

$items = $model->get()->toArray();

$document = $this->container->platform->getDocument();

/** @var JDocumentJSON $document */
if ($document instanceof Document) {
	$document->setMimeEncoding('application/json');
	$document->setName('attendees');
}

echo json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
