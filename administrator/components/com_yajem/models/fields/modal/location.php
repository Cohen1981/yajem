<?php
/**
 * @package     Yajem.Administrator
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

jimport('joomla.form.formfield');
/**
 * Supports a modal location picker.
 *
 * @since  version
 */
class JFormFieldModal_Location extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   version
	 */
	protected $type = 'Modal_Location';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   version
	 */
	protected function getInput()
	{
		// The active location id field.
		$value = (int) $this->value > 0 ? (int) $this->value : '';

		// Create the modal id.
		$modalId = 'Location_' . $this->id;

		// Add the modal field script to the document head.
		HtmlHelper::_('jquery.framework');
		HtmlHelper::_('script', 'system/modal-fields.js', array('version' => 'auto', 'relative' => true));

		// Script to proxy the select modal function to the modal-fields.js file.
		static $scriptSelect = null;

		if (is_null($scriptSelect))
		{
			$scriptSelect = array();
		}

		if (!isset($scriptSelect[$this->id]))
		{
			JFactory::getDocument()->addScriptDeclaration("
			function jSelectLocation_" . $this->id . "(id, title, object) {
				window.processModalSelect('Location', '" . $this->id . "', id, title, '', object);
			}
			"
			);

			$scriptSelect[$this->id] = true;
		}

		// Setup variables for display.
		$linkLocations = 'index.php?option=com_yajem&amp;view=locations&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1';

		$modalTitle = JText::_('COM_YAJEM_CHANGE_LOCATION');

		$urlSelect = $linkLocations . '&amp;function=jSelectLocation_' . $this->id;

		if ($value)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('title'))
				->from($db->quoteName('#__yajem_locations'))
				->where($db->quoteName('id') . ' = ' . (int) $value);
			$db->setQuery($query);

			try
			{
				$title = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				echo $e->getMessage();
			}
		}

		$title = empty($title) ? JText::_('COM_YAJEM_SELECT_A_LOCATION') : htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The current location display field.
		$html = '<span class="input-append">';
		$html .= '<input class="input-medium" id="' . $this->id . '_name" type="text" value="' . $title . '" disabled="disabled" size="35" />';

		// Select location button
		$html .= '<a'
			. ' class="btn hasTooltip"'
			. ' id="' . $this->id . '_select"'
			. ' data-toggle="modal"'
			. ' role="button"'
			. ' href="#ModalSelect' . $modalId . '"'
			. ' title="' . HtmlHelper::tooltipText('COM_YAJEM_CHANGE_LOCATION') . '">'
			. '<span class="icon-file" aria-hidden="true"></span> ' . JText::_('JSELECT')
			. '</a>';

		$html .= '</span>';

		// Select location modal
		$html .= HtmlHelper::_(
			'bootstrap.renderModal',
			'ModalSelect' . $modalId,
			array(
				'title'      => $modalTitle,
				'url'        => $urlSelect,
				'height'     => '400px',
				'width'      => '800px',
				'bodyHeight' => '70',
				'modalWidth' => '80',
				'footer'     => '<a role="button" class="btn" data-dismiss="modal" aria-hidden="true">' . JText::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>',
			)
		);

		// Note: class='required' for client side validation.
		$class = $this->required ? ' class="required modal-value"' : '';

		$html .= '<input type="hidden" id="' . $this->id . '_id"' . $class . ' data-required="' . (int) $this->required . '" name="' . $this->name
			. '" data-text="' . htmlspecialchars(JText::_('COM_YAJEM_SELECT_A_LOCATION', true), ENT_COMPAT, 'UTF-8') . '" value="' . $value . '" />';

		return $html;
	}

	/**
	 *
	 * @return mixed|string
	 *
	 * @since 1.0
	 */
	protected function getLabel()
	{
		return str_replace($this->id, $this->id . '_id', parent::getLabel());
	}
}