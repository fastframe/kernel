<?php

/**
 * @file
 * Contains \FastFrame\Kernel\Provider
 */

namespace FastFrame\Kernel;

use Interop\Container\ContainerInterface;

/**
 * Contract for Configuration Providers
 *
 * This is similar to the Aura/DI except it uses Container Interop.
 *
 * @package FastFrame\Kernel
 */
interface Provider
{
	/**
	 * Define params, setters, and services before the Container is locked.
	 *
	 * @param ContainerInterface $container The DI container.
	 * @return void
	 */
	public function define(ContainerInterface $container);

	/**
	 * Modify service objects after the Container is locked.
	 *
	 * @param ContainerInterface $container The DI container.
	 * @return void
	 */
	public function modify(ContainerInterface $container);
}