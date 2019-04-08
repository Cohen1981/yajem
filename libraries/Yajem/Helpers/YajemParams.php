<?php
/**
 * @package     Joomla\Component\Yajem\Administrator\Helpers
 *
 * @copyright   2018 Alexander Bahlo
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Yajem\Helpers;

use Joomla\CMS\Component\ComponentHelper;

/**
 * Helper Class holding all parameters for yajem component
 *
 * @since       version
 */
class YajemParams
{
	/**
	 * @var boolean|null
	 * @since version
	 */
	public $useOrg = null;

	/**
	 * @var boolean|null
	 * @since version
	 */
	public $useHost = null;

	/**
	 * @var boolean|null
	 * @since version
	 */
	public $useComments = null;

	/**
	 * @var string|null
	 * @since version
	 */
	public $googleApiKey = null;

	/**
	 * @var bool|null
	 * @since version
	 */
	public $useModalLocation = null;

	/**
	 * @var bool|null
	 * @since version
	 */
	public $showPastEvents = null;

	/**
	 * @var bool|null
	 * @since version
	 */
	public $useUserProfile = null;

	/**
	 * @var bool|null
	 * @since version
	 */
	public $useLocationContact = null;

	/**
	 * @var bool|null
	 * @since 1.2.0
	 */
	public $useAjaxCalls = null;

	/**
	 * YajemParams constructor.
	 * @since 1.2.0
	 */
	public function __construct()
	{
		$this->useOrg  = (bool) ComponentHelper::getParams('com_yajem')->get('use_organizer');
		$this->useHost = (bool) ComponentHelper::getParams('com_yajem')->get('use_host');
		$this->useComments  = (bool) ComponentHelper::getParams('com_yajem')->get('use_comments');
		$this->googleApiKey = (string) ComponentHelper::getParams('com_yajem')->get('global_googleapi');
		$this->useModalLocation = (bool) ComponentHelper::getParams('com_yajem')->get('use_modal_location');
		$this->showPastEvents = (bool) ComponentHelper::getParams('com_yajem')->get('show_pastEvents');
		$this->useUserProfile = (bool) ComponentHelper::getParams('com_yajem')->get('use_user_profile');
		$this->useLocationContact = (bool) ComponentHelper::getParams('com_yajem')->get('use_location_contact');
		$this->useAjaxCalls = (bool) ComponentHelper::getParams('com_yajem')->get('use_ajax_calls');
	}
}