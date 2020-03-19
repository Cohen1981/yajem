<?php
/**
 * @package     Sda\Jem\Admin\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Admin\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use Joomla\CMS\Factory;

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.1.1
 *
 * Model Sda\Jem\Admin\Model\Mailing
 *
 * Fields:
 *
 * @property   int			$sdajem_mailing_id
 * @property   int			$sdajem_event_id
 * @property   int		    $users_user_id
 * @property   int		    $subscribed
 *
 * Relations:
 *
 * @property  Event         $event
 * @property  User          $user
 */
class Mailing extends DataModel
{
	/**
	 * Mailing constructor.
	 *
	 * @param   Container $container The Container
	 * @param   array     $config    The Configuration
	 *
	 * @since 0.1.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
		$this->hasOne('user', 'User', 'users_user_id', 'id');
		$this->hasOne('event', 'Event', 'sdajem_evet_id', 'sdajem_event_id');
	}

	/**
	 * Get the Subscription for User and Event, or empty record if not exist
	 *
	 * @param   int $eventId    Event Id
	 * @param   int $userId     User Id
	 *
	 * @return void
	 * @since 0.1.1
	 */
	public function getSubscriptionForUserAndEvent(int $eventId, int $userId)
	{
		$dbo = Factory::getDbo();
		$query = $dbo->getQuery(true);

		$query->select('sdajem_mailing_id')
			->from('#__sdajem_mailings')
			->where(array('sdajem_event_id=' . $eventId, 'users_user_id=' . $userId));
		$dbo->setQuery($query);
		$id = $dbo->loadResult();

		// Calling load with null value gets a random row. So we don't even call load when null.
		if ($id != null)
		{
			$this->load($id);
		}
	}
}