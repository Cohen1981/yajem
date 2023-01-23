<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\HTML\Registry;
use Sda\Component\Sdajem\Administrator\Extension\SdajemComponent;

return new class implements ServiceProviderInterface {

	public function register(Container $container): void {
		$container->registerServiceProvider(new CategoryFactory('\\Sda\\Component\\Sdajem'));
		$container->registerServiceProvider(new MVCFactory('\\Sda\\Component\\Sdajem'));
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\Sda\\Component\\Sdajem'));
		$container->set(
			ComponentInterface::class,
			function (Container $container) {
				$component = new SdajemComponent($container->get(ComponentDispatcherFactoryInterface::class));

				$component->setRegistry($container->get(Registry::class));
				$component->setMVCFactory($container->get(MVCFactoryInterface::class));
				$component->setCategoryFactory($container->get(CategoryFactoryInterface::class));

				return $component;
			}
		);
	}
};