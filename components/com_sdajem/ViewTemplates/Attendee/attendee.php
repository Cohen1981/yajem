<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */
/** @var \Sda\Jem\Site\View\Attendee\Raw    $this        */
/** @var \Sda\Jem\Site\Model\Attendee       $attendee    */

$input    = $this->input->request->getArray();
$attendee = $this->getModel();
$attendee->load($input['id]']);

echo $attendee->getAttendingHtml();
