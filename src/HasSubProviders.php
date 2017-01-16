<?php

/**
 * @file
 * Contains \FastFrame\Kernel\HasSubProviders
 */

namespace FastFrame\Kernel;

use Interop\Container\ContainerInterface;

/**
 * Trait for using sub providers in a provider
 *
 * @package FastFrame\Kernel
 */
trait HasSubProviders
{
	/**
	 * @var ProviderList
	 */
	protected $providers;

	/**
	 * Initializes the provider list
	 *
	 * @param Environment $env
	 * @param null|string $namespace
	 * @return ProviderList
	 */
	protected function initProviderList(Environment $env, $namespace = null)
	{
		return new ProviderList($env, $namespace ?: $env->get(Environment::KEY_CONFIG_NAMESPACE));
	}

	/**
	 * Runs the define method on the providers
	 *
	 * @param ContainerInterface $container
	 */
	public function runProviderDefine(ContainerInterface $container)
	{
		$this->providers->define($container);
	}

	/**
	 * Runs the modify method on the providers
	 *
	 * @param ContainerInterface $container
	 */
	public function runProviderModify(ContainerInterface $container)
	{
		$this->providers->modify($container);
	}
}