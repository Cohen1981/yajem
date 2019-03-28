<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */
/** @var \Sda\Jem\Site\View\Comment\Raw    $this       */
/** @var \Sda\Jem\Site\Model\Comment       $comment    */

$input = $this->input->request->getArray();
$comment = $this->getModel();
$comment->load($input['id]']);

echo $comment->getCommentHtml();
