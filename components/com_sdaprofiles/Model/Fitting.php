<?php
/**
 * @package     Sda\Profiles\Admin\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Profiles\Site\Model;

use FOF30\Container\Container;
use Sda\Profiles\Admin\Model\Fitting as AdminFitting;

/**
 * @package     Sda\Profiles\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Profiles\Site\Model\Fitting
 *
 */
class Fitting extends AdminFitting
{
	/**
	 *
	 * @return string
	 *
	 * @since 0.0.1
	 */
	public function getHtml()
	{
		$html = "<div id=\"" . $this->sdaprofiles_fitting_id . "\" class=\"sdaprofiles_flex_row\">" .
				"<div class=\"sdaprofiles_cell\">" . $this->type . "</div>" .
				"<div class=\"sdaprofiles_cell\">" . $this->detail . "</div>" .
				"<div class=\"sdaprofiles_cell\">" . $this->length . " x " . $this->width . "</div>" .
				"</div>";

		return $html;
	}
}