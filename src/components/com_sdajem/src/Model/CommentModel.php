<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * @package     Sda\Component\Sdajem\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Model;

use DateTime;
use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Sda\Component\Sdajem\Site\Model\Item\Comment;
use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Event model for the Joomla Events component.
 *
 * @since  1.0.0
 *
 */

class CommentModel extends BaseDatabaseModel
{
	/**
	 * @var string item
	 * @since 1.0.0
	 */
	protected $_item = null;

	/**
	 * Gets a event
	 *
	 * @param   null  $pk  Id for the event
	 *
	 * @return  mixed Object or null
	 *
	 * @throws Exception
	 * @since   1.0.0
	 */
	public function getItem($pk = null)
	{
		$app = Factory::getApplication();
		$pk  = ($pk) ? $pk : $app->input->getInt('id');

		if ($this->_item === null)
		{
			$this->_item = [];
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{
				$db    = $this->getDatabase();
				$query = $db->getQuery(true);

				$query->select('*')
					->from($db->quoteName('#__sdajem_comments', 'c'))
					->where($db->quoteName('c.id') . ' = :commentId');

				$query->bind(':commentId', $pk);

				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data))
				{
					throw new Exception(Text::_('COM_SDAJEM_ERROR_ATTENDING_NOT_FOUND'), 404);
				}

				$this->_item[$pk] = $data;
			}
			catch (Exception $e)
			{
				$app->enqueueMessage($e->getMessage(),'error');
				$this->_item[$pk] = false;
			}
		}

		return Comment::createFromObject($this->_item[$pk]);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @throws Exception
	 * @since   1.0.0
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		$this->setState('comment.id', $app->input->getInt('id'));
		$this->setState('params', $app->getParams());
	}
}