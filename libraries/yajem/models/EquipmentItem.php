<?php
/**
 * @package     Yajem\Models
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Yajem\Models;

defined('_JEXEC') or die;

/**
 * @package     Yajem\Models
 *
 * @since       version
 */
class EquipmentItem
{
	/**
	 * @var int|null
	 * @since 1.3.0
	 */
	public $id = null;

	/**
	 * @var string|null
	 * @since 1.3.0
	 */
	public $type = null;

	/**
	 * @var string|null
	 * @since 1.3.0
	 */
	public $detail = null;

	/**
	 * @var string|null
	 * @since 1.3.0
	 */
	public $length = null;

	/**
	 * @var string|null
	 * @since 1.3.0
	 */
	public $width = null;

	/**
	 * EquipmentItem constructor.
	 *
	 * @param   string  $type   Type
	 * @param   string  $detail Detail
	 * @param   string  $length Length
	 * @param   string  $width  Width
	 *
	 * @since 1.3.0
	 */
	public function __construct(string $type, string $detail, string $length, string $width)
	{
		$this->type = $type;
		$this->detail = $detail;
		$this->length = $length;
		$this->width = $width;
	}

	/**
	 * @param   EquipmentItem $item The item
	 *
	 * @return EquipmentItem
	 *
	 * @since 1.3.0
	 */
	public static function cast(EquipmentItem $item) : EquipmentItem
	{
		return $item;
	}
}