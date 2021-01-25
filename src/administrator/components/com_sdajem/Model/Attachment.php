<?php
/**
 * @package     Sda\Jem\Admin\Model
 * @subpackage
 *
 * @copyright   Alexander Bahlo
 * @license     GPL2
*/

namespace Sda\Jem\Admin\Model;

use FOF30\Date\Date;
use FOF30\Model\DataModel;
use Joomla\CMS\Filesystem\File;

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\Attachment
 *
 * Fields:
 *
 * @property   int			$sdajem_attachment_id
 * @property   int			$sdajem_event_id
 * @property   int			$sdajem_location_id
 * @property   string		$file
 * @property   string		$title
 * @property   string		$description
 * @property   int			$access
 * @property   int			$enabled
 * @property   int			$locked_by
 * @property   Date			$locked_on
 * @property   int			$hits
 * @property   int			$ordering
 * @property   Date			$created_on
 * @property   int			$created_by
 * @property   Date			$modified_on
 * @property   int			$modified_by
*/
class Attachment extends DataModel
{
	public function uploadFile(string $file)
	{
		File::upload(File::makeSafe($file),'media/com_sdajem');
	}
}
