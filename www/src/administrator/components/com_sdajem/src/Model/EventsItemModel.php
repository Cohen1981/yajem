<?php
/**
 * @package     Sda\Component\Sdajem\Administrator\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Component\Sdajem\Administrator\Model;

use Joomla\CMS\Date\Date;

/**
 * @since       version
 * @package     Sda\Component\Sdajem\Administrator\Model
 *
 * @property  int       id
 * @property  int       access
 * @property  string    alias
 * @property  Date      created
 * @property  int       published
 * @property  Date      publish_up
 * @property  Date      publish_down
 * @property  int       ordering
 * @property  string    language
 * @property  string    title
 * @property  string    description
 * @property  string    image
 * @property  int       sdajem_location_id fk to locations table
 * @property  string    location_name
 * @property  Date      startDateTime
 * @property  Date      endDateTime
 * @property  int       allDayEvent
 * @property  int       eventStatus
 * @property  int       catid
 * @property  string    category_title
 */
class EventsItemModel
{

}