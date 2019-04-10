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
use Joomla\CMS\Component\ComponentHelper;
use Sda\Profiles\Admin\Model\Profile;
use Joomla\CMS\Factory;

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\User
 *
 * Adapter to Joomlas user Table. Enriching the User Model with SDA Profile if active
 *
 * Fields:
 *
 * @property  int       $id
 * @property  string    $name
 * @property  string    $username
 * @property  string    $email
 *
 * Relations:
 *
 * @property  Profile   $profile
 * @property  Mailing   $subscriptions
 */
class User extends DataModel
{
	/**
	 * User constructor.
	 *
	 * @param   Container $container    The Container
	 * @param   array     $config       The Configuration
	 *
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		$config['tableName'] = '#__users';
		$config['idFieldName'] = 'id';
		parent::__construct($container, $config);

		$this->hasMany('attendees', 'Attendee', 'id', 'users_user_id');
		$this->hasMany('subscriptions', 'Mailing', 'id', 'users_user_id');

		if (ComponentHelper::isEnabled('com_sdaprofiles'))
		{
			$this->hasOne('profile', 'Profile@com_sdaprofiles', 'id', 'users_user_id');
		}

	}

	/**
	 *
	 * @return string
	 *
	 * @since 0.0.2
	 */
	public function getLinkToContact() : string
	{
		if ($this->profile && !Factory::getUser()->guest)
		{
			$link = "<a href=\"index.php?option=com_sdaprofiles&view=Profiles&task=read&id=" . $this->profile->sdaprofiles_profile_id . "\">" .
				$this->profile->userName . "</a>";
		}
		else
		{
			$link = $this->username;
		}

		return $link;
	}
}