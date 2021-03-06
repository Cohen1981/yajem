<?php
/**
 * @package     Sda\Profiles\Admin\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Profiles\Admin\Model;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use Sda\Jem\Admin\Model\Attendee;
use Joomla\CMS\Language\Text;

/**
 * @package     Sda\Profiles\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Profiles\Admin\Model\Fitting
 *
 * Fields:
 *
 * @property  int       $sdaprofiles_fitting_id
 * @property  int       $sdaprofiles_profile_id
 * @property  int       $type
 * @property  int       $sdaprofiles_fitting_image_id
 * @property  string    $detail
 * @property  double    $length
 * @property  double    $width
 * @property  boolean   $standard
 *
 * Filters:
 *
 * @method  $this  sdaprofiles_fitting_id() sdaprofiles_profile_id(int $v)
 * @method  $this  enabled()                enabled(bool $v)
 * @method  $this  created_on()             created_on(string $v)
 * @method  $this  created_by()             created_by(int $v)
 * @method  $this  modified_on()            modified_on(string $v)
 * @method  $this  modified_by()            modified_by(int $v)
 * @method  $this  locked_on()              locked_on(string $v)
 * @method  $this  locked_by()              locked_by(int $v)
 *
 * Relations:
 *
 * @property  FittingImage  $image
 * @property  Profile       $profile
 * @property  FittingType   $typeModel
 */
class Fitting extends DataModel
{
	/**
	 * Fitting constructor.
	 *
	 * @param   Container $container    The Container
	 * @param   array     $config       The Configuration
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
		$this->addBehaviour('Filters');
		$this->belongsTo('profile', 'Profile');
		$this->hasOne('image', 'FittingImage', 'sdaprofiles_fitting_image_id', 'sdaprofiles_fitting_image_id');
		$this->hasOne('typeModel', 'FittingType', 'type', 'sdaprofiles_fitting_type_id');
	}

	/**
	 *
	 * @return float
	 *
	 * @since 0.0.1
	 */
	public function getRequiredSpace() : float
	{
		if ($this->length && $this->width)
		{
			return $this->length * $this->width;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Enforcing data sanity
	 *
	 * @return void
	 *
	 * @since 0.1.1
	 */
	protected function onBeforeDelete()
	{
		if ($this->profile->attendees)
		{
			$id = $this->input->get('id');
			$attendees = $this->profile->attendees;
			/** @var Attendee $attendee */
			foreach ($attendees as $attendee)
			{
				$key = array_search($id, $attendee->sdaprofilesFittingIds);

				if ($key !== false)
				{
					$equipment = $attendee->sdaprofilesFittingIds;
					unset($equipment[$key]);
					$attendee->sdaprofilesFittingIds = $equipment;
					$attendee->save();
				}
			}
		}
	}

	/**
	 * @param   boolean $value Value
	 *
	 * @return integer
	 *
	 * @since 0.2.8
	 */
	protected function setStandardAttribute($value)
	{
		return ($value) ? 1 : 0;
	}

	/**
	 * @param   int $value Value
	 *
	 * @return boolean
	 *
	 * @since 0.2.8
	 */
	protected function getStandardAttribute($value)
	{
		return ($value == 1) ? true : false;
	}
}