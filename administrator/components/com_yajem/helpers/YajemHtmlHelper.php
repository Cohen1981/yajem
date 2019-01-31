<?php
/**
 * @package     Yajem\Administrator\Helpers
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomla\Component\Yajem\Administrator\Helpers;

use Joomla\CMS\Language\Text;
use Joomla\Component\Yajem\Administrator\Classes\YajemEvent;

require_once JPATH_ADMINISTRATOR . '/components/com_yajem/helpers/YajemEventParams.php';

/**
 * @package     Yajem\Administrator\Helpers
 *
 * @since       1.2.0
 */
class YajemHtmlHelper
{
	/**
	 * @var \Joomla\Component\Yajem\Administrator\Helpers\YajemEventParams|null
	 * @since 1.2.0
	 */
	public $params  = null;

	/**
	 * @var \stdClass|null
	 * @since 1.2.0
	 */
	public $links   = null;

	/**
	 * @var \stdClass|null
	 * @since 1.2.0
	 */
	public $symbols = null;

	/**
	 * @var YajemParams|null
	 * @since 1.2.0
	 */
	private $yajemParams = null;

	/**
	 * YajemHtmlHelper constructor.
	 *
	 * @param   null|YajemEvent $event Event Object
	 *
	 * @since 1.2.0
	 */
	public function __construct($event = null)
	{
		$this->yajemParams  = new YajemParams;
		$this->params       = new YajemEventParams($event);
		$this->symbols      = new \stdClass;
		$this->links        = new \stdClass;

		self::getSymbols();
		self::getLinks();
	}

	/**
	 * @return void
	 *
	 * @since version
	 */
	private function getSymbols()
	{
			$this->symbols->open        = '<i class="fas fa-question-circle" aria-hidden="true" title="' .
											Text::_("COM_YAJEM_EVENT_STATUS_OPEN") . '"></i>';
			$this->symbols->confirmed   = '<i class="far fa-thumbs-up" aria-hidden="true" title="' .
											Text::_("COM_YAJEM_EVENT_STATUS_CONFIRMED") . '"></i>';
			$this->symbols->canceled    = '<i class="far fa-thumbs-down" aria-hidden="true" title="' .
											Text::_("COM_YAJEM_EVENT_STATUS_CANCELED") . '"></i>';
	}

	/**
	 *
	 * @return void
	 *
	 * @since version
	 */
	private function getLinks()
	{
		if ($this->yajemParams->useAjaxCalls)
		{
			$this->links->confirm       = '<label id="yajem_confirm" class="green" onclick="switchEventStatus(\'confirm\')">' .
				$this->symbols->confirmed . '</label>';
			$this->links->cancel        = '<label id="yajem_canc" class="crimson" onclick="switchEventStatus(\'cancel\')">' .
				$this->symbols->canceled . '</label>';
		}
		else
		{
			$this->links->confirm       = '<label id="yajem_confirm" class="green" for="eConfirm">' .
				$this->symbols->confirmed . '</label>';
			$this->links->cancel        = '<label id="yajem_canc" class="crimson" for="eCancel">' .
				$this->symbols->canceled . '</label>';
		}
	}
}