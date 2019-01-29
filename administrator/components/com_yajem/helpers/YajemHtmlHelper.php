<?php
/**
 * @package     Yajem\Administrator\Helpers
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Yajem\Administrator\Helpers;

use Joomla\CMS\Language\Text;
use Yajem\Administrator\Models\YajemModelEvent;

require_once JPATH_ADMINISTRATOR . '/components/com_yajem/helpers/YajemEventParams.php';

/**
 * @package     Yajem\Administrator\Helpers
 *
 * @since       1.2.0
 */
class YajemHtmlHelper
{
	/**
	 * @var \Yajem\Administrator\Helpers\YajemEventParams|null
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
	 * YajemHtmlHelper constructor.
	 *
	 * @param   null|YajemModelEvent $event   Event Object
	 *
	 * @since 1.2.0
	 */
	public function __construct($event = null)
	{
		$this->params   = new YajemEventParams($event);
		$this->symbols  = new \stdClass;
		$this->links    = new \stdClass;

		if ($event)
		{
			self::getEventParams($event);
		}
	}

	/**
	 * @param   null|YajemModelEvent $event Event Object
	 *
	 * @return void
	 *
	 * @since version
	 */
	public function getEventParams($event)
	{

		if ($this->params->useOrg)
		{
			$this->symbols->open        = '<i class="fas fa-question-circle" aria-hidden="true" title="' .
											Text::_("COM_YAJEM_EVENT_STATUS_OPEN") . '"></i>';
			$this->symbols->confirmed   = '<i class="far fa-thumbs-up" aria-hidden="true" title="' .
											Text::_("COM_YAJEM_EVENT_STATUS_CONFIRMED") . '"></i>';
			$this->symbols->canceled    = '<i class="far fa-thumbs-down" aria-hidden="true" title="' .
											Text::_("COM_YAJEM_EVENT_STATUS_CANCELED") . '"></i>';

			$this->links->confirm       = '<label id="yajem_confirm" class="green" for="eConfirm">' .
											$this->symbols->confirmed . '</label>';
			$this->links->cancel        = '<label id="yajem_canc" class="crimson" for="eCancel">' .
											$this->symbols->canceled . '</label>';
		}

	}
}