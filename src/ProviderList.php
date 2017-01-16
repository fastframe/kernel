<?php

/**
 * @file
 * Contains \FastFrame\Kernel\ProviderList
 */

namespace FastFrame\Kernel;

use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use Traversable;

class ProviderList
	implements \Countable
{
	/**
	 * The list of providers
	 *
	 * @var array
	 */
	protected $providers = [];

	/**
	 * The environment of the kernel
	 *
	 * @var Environment
	 */
	protected $env;

	/**
	 * The namespace to use for constructing providers
	 *
	 * @var string
	 */
	protected $namespace;

	public function __construct(Environment $env, $namespace = null)
	{
		$this->env       = $env;
		$this->namespace = $namespace ?: $env->get(Environment::KEY_CONFIG_NAMESPACE);
	}

	/**
	 * Returns the count of providers in the list
	 *
	 * @return int
	 */
	public function count()
	{
		return count($this->providers);
	}

	/**
	 * Returns the list of providers
	 *
	 * @return array
	 */
	public function all()
	{
		return $this->providers;
	}

	/**
	 * Runs the define command on the providers with the given container
	 *
	 * @param ContainerInterface $container
	 */
	public function define(ContainerInterface $container)
	{
		foreach ($this->providers as $provider) {
			$provider->define($container);
		}
	}

	/**
	 * Runs the modify command on the providers with the given container
	 *
	 * @param ContainerInterface $container
	 */
	public function modify(ContainerInterface $container)
	{
		foreach ($this->providers as $provider) {
			$provider->modify($container);
		}
	}

	/**
	 * Appends a provider to the list of providers
	 *
	 * @param Provider|string $provider
	 */
	public function append($provider)
	{
		array_push($this->providers, $this->resolveProvider($provider));
	}

	/**
	 * Prepends a provider to the list of providers
	 *
	 * @param Provider|string $provider
	 */
	public function prepend($provider)
	{
		array_unshift($this->providers, $this->resolveProvider($provider));
	}

	/**
	 * Resolves the provider from either a string or checks that it is an instance of a Provider
	 *
	 * @param string|Provider $name
	 * @return Provider@
	 * @throws InvalidArgumentException When the provider cannot be initialized or isn't a Provider
	 */
	protected function resolveProvider($name)
	{
		if (is_string($name)) {
			if (strpos($name, '\\_Config\\') === false) {
				$name = "{$this->namespace}\\$name";
			}

			if (class_exists($name)) {
				$name = new $name($this->env);
			}
		}

		if ($name instanceof Provider) {
			return $name;
		}

		throw new InvalidArgumentException("Invalid Provider " . (is_object($name) ? get_class($name) : $name));
	}
}