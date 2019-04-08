<?php
/**
 * @package     Joomla\Component\Yajem\Administrator\Classes
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomla\Component\Yajem\Administrator\Classes;

use DateTime;

/**
 * @package     Joomla\Component\Yajem\Administrator\Classes
 *
 * @since       version
 */
class YajemLocation
{
	/**
	 * @var		int|null
	 * @since	version
	 */
	public $id = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $catid = null;

	/**
	 * @var		int|bool|null
	 * @since	version
	 */
	public $published = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $ordering = null;

	/**
	 * @var		DateTime|null
	 * @since	version
	 */
	public $created = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $createdBy = null;

	/**
	 * @var		DateTime|null
	 * @since	version
	 */
	public $modified = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $modifiedBy = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $title = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $alias = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $description = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $url = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $street = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $postalCode = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $city = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $stateAddress = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $country = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $latlng = null;

	/**
	 * @var		int|null
	 * @since	version
	 */
	public $contactid = null;

	/**
	 * @var		String|null
	 * @since	version
	 */
	public $image = null;
}