<?php
/**
 * @package     Sda\Component\Sdajem\Site\Helper
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Site\Helper;

\defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\Component\Categories\Administrator\Helper\CategoryAssociationHelper;
use Sda\Component\Sdajem\Site\Helper\RouteHelper;

abstract  class AssociationHelper extends CategoryAssociationHelper
{
	/**
	 * Method to get the associations for a given item
	 *
	 * @param   integer  $id    Id of the item
	 * @param   string   $view  Name of the view
	 *
	 * @return  array   Array of associations for the item
	 *
	 * @since  __BUMP_VERSION__
	 */
	public static function getAssociations($id = 0, $view = null)
	{
		$jinput = Factory::getApplication()->input;
		$view = $view ?? $jinput->get('view');
		$id = empty($id) ? $jinput->getInt('id') : $id;
		if ($view === 'events') {
			if ($id) {
				$associations = Associations::getAssociations('com_sdajem', '#__sdajem_events', 'com_sdajem.item', $id);
				$return = [];
				foreach ($associations as $tag => $item) {
					$return[$tag] = RouteHelper::getEventsRoute($item->id, (int) $item->catid, $item->language);
				}
				return $return;
			}
		}
		if ($view === 'category' || $view === 'categories') {
			return self::getCategoryAssociations($id, 'com_sdajem.events');
		}
		return [];
	}
}