<?php
/**
 * @package     Yajem.Administrator
 * @subpackage  Yajem
 *
 * @copyright   2018 Alexander Bahlo
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');

/**
 * @package     COM_YAJEM
 *
 * @since       1.0
 */
class JFormFieldAttendees extends JFormFieldCheckboxes
{
	/**
	 * @var string Name of the custom Field
	 * @since 1.0
	 */
	protected $type = 'Attendees';

	/**
	 * Get the List of events for selection
	 * @return array    key->value
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	public function getOptions()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, name')->from('`#__users`');

		$users = $db->setQuery($query)->loadObjectlist();
		$regUsers = $this->checkedOptions;

		foreach ($users as $user)
		{
			if (in_array($user->id, $regUsers)) {
				// user is invited and should stay as is -> for that we disable the checkbox
				$attendees[] = JHtml::_('select.option', $user->id, $user->name, 'value', 'text', true);
			} else {
				$attendees[] = JHtml::_('select.option', $user->id, $user->name);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $attendees);

		return $options;
	}

	/**
	 * extended for getting the checkedOptions
	 *
	 * @return array
	 *
	 * @since 1.0
	 * @throws Exception
	 */
	protected function getLayoutData()
	{
		$app = JFactory::getApplication();
		// if no event given use -1 in where clause
		$event = ($app->input->get('id') != null ? $app->input->get('id') : -1);
		$db = JFactory::getDbo();
		$query2 = $db->getQuery(true);
		$query2->select('userId')->from('`#__yajem_attendees`');
		$query2->where('eventId = ' . $event);

		$this->checkedOptions = $db->setQuery($query2)->loadColumn();
		return parent::getLayoutData();
	}

}