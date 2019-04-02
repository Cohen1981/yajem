<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

/** @var \Sda\Jem\Site\View\Event\Html   $this       */

$this->addJavascriptFile('media://com_sdajem/js/eventForm.js');

echo $this->getRenderedForm();
