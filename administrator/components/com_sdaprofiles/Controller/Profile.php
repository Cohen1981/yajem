<?php
/**
 * @package     Sda\Profiles\Controller
 *
 * @copyright   2019 Alexander Bahlo
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Sda\Profiles\Admin\Controller;

use FOF30\Container\Container;
use FOF30\Controller\DataController;

/**
 * @package     Sda\Profiles\Controller
 *
 * @since       0.0.1
 */
class Profile extends DataController
{
	/**
	 * Profile constructor.
	 *
	 * @param   Container $container    Container
	 * @param   array     $config       Configuration
	 * @since 0.0.1
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
	}
}