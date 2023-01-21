<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_sdajem
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sda\Component\Sdajem\Administrator\Extension;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Sda\Component\Sdajem\Administrator\Service\HTML\AdministratorService;
use Psr\Container\ContainerInterface;

/**
 * Component class for com_sdajem
 *
 * @since  __BUMP_VERSION__
 */
class SdajemComponent extends MVCComponent implements BootableExtensionInterface, CategoryServiceInterface
{
	use CategoryServiceTrait;
	use HTMLRegistryAwareTrait;

	/**
	 * Booting the extension. This is the function to set up the environment of the extension like
	 * registering new class loaders, etc.
	 *
	 * If required, some initial set up can be done from services of the container, eg.
	 * registering HTML services.
	 *
	 * @param   ContainerInterface  $container  The container
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function boot(ContainerInterface $container)
	{
		$this->getRegistry()->register('sdajemadministrator', new AdministratorService);
	}

	public function countItems(array $items, string $section)
	{
		try
		{
			$config = (object) array(
				'related_tbl'   => $this->getTableNameForSection($section),
				'state_col'     => 'published',
				'group_col'     => 'catid',
				'relation_type' => 'category_or_group',
			);
			ContentHelper::countRelations($items, $config);
		}
		catch (\Exception $e)
		{
			// Ignore it
		}
	}

	protected function getTableNameForSection(string $section = null)
	{
		return ($section === 'category' ? 'categories' : 'sda_events');
	}

	protected function getStateColumnForSection(string $section = null)
	{
		return 'published';
	}
}