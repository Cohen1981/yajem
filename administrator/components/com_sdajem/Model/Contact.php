<?php
/**
 * @package     Sda\Jem\Admin\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Jem\Admin\Model;

use FOF30\Model\DataModel;
use FOF30\Container\Container;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;

/**
 * @package     Sda\Jem\Admin\Model
 *
 * @since       0.0.1
 *
 * Model Sda\Jem\Admin\Model\User
 *
 * Fields:
 *
 * @property  int       $id
 * @property  string    $name
 */
class Contact extends DataModel
{
	/**
	 * @var boolean
	 * @since 0.0.1
	 */
	private $sdacontacts=false;

	/**
	 * Contact constructor.
	 *
	 * @param   Container $container    The Container
	 * @param   array     $config       The Configuration
	 *
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		$sdajemParams = ComponentHelper::getParams('com_sdajem');

		if ((bool) $sdajemParams->get('use_sdacontacts') && ComponentHelper::isEnabled('com_sdacontacts'))
		{
			$this->sdacontacts = true;
			$config['tableName'] = '#__sdacontacts_contacts';
			$config['idFieldName'] = 'sdacontacts_contact_id';
			$aliasFields = array();
			array_add($aliasFields, 'id', 'sdacontacts_contact_id');
			array_add($aliasFields, 'name', 'title');
			$config['aliasFields'] = $aliasFields;
		}
		else
		{
			$config['tableName'] = '#__contact_details';
			$config['idFieldName'] = 'id';
		}

		parent::__construct($container, $config);
	}

	/**
	 *
	 * @return string
	 *
	 * @since 0.0.2
	 */
	public function getLinkToContact() : string
	{
		if ($this->sdacontacts)
		{
			$link = "<a href=\"index.php?option=com_sdacontacts&view=Contacts&task=read&id=" . $this->id . "\">" .
				$this->title . "</a>";
		}
		else
		{
			$link = "<a href=\"index.php?option=com_contact&view=contact&id=" . $this->id . "\">" .
					$this->name . "</a>";
		}

		return $link;
	}
}