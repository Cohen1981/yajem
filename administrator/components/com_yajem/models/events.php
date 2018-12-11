<?php
/**
 * @package    yajem
 *
 * @author     abahl <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

/**
 * Yajem
 *
 * @package  yajem
 * @since    1.0
 */
class YajemModelEvents extends ListModel
{
	/**
	 * YajemModelEvents constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @since   1.0
	 */
	public function __construct(array $config = array())
	{
		if (empty($config['filter_fields']))
		{
			// Add the standard ordering filtering fields whitelist.
			// Note to self. Must be exactly written as in the default.php. 'a.`field`' not the same as 'a.field'
			$config['filter_fields'] = array(
				'id', 'a.id',
				'published', 'a.published',
				'ordering', 'a.ordering',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'title','a.title',
				'catid', 'a.catid',
				'image', 'a.image',
				'hoster','a.hoster',
				'organizer', 'org.name',
				'startDate', 'a.startDate',
				'endDate', 'a.endDate',
				'startDateTime', 'a.startDateTime',
				'endDateTime', 'a.endDateTime'
			);
		}

		parent::__construct($config);
	}
}
