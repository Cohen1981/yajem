<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Rule
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Rule;

\defined('_JEXEC') or die;

use Joomla\CMS\Form\FormRule;

class LetterRule extends FormRule
{
	/**
	 * The regular expression to use in testing a form field value.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $regex = '^([a-z]+)$';
	/**
	 * The regular expression modifiers to use when testing a form field value.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $modifiers = 'i';
}