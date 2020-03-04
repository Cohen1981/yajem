<?php
/**
 * @package     Sda\Jem\Site\View\Fitting
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Site\View\Comment;

use FOF30\View\DataView\Raw as BaseRaw;
use Sda\Jem\Site\Model\Comment;

/**
 * @package     Sda\Profiles\Site\View\Comment
 *
 * @since       0.0.1
 */
class Raw extends BaseRaw
{
	/**
	 *
	 * @return void
	 *
	 * @since 0.0.1
	 */
	public function onBeforeMain()
	{
		$input = $this->input->request->getArray();

		switch ($input['task'])
		{
			case "commentAjax":
				/** @var Comment $comment */
				$comment = $this->getModel();
				$comment->load($input['id]']);
				$this->setLayout('comment');
				break;
			case "error":
				$this->setLayout('error');
				break;
		}
	}
}