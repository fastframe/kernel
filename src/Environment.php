<?php

/**
 * @file
 * Contains \FastFrame\Kernel\Environment
 */

namespace FastFrame\Kernel;

use Composer\Autoload\ClassLoader;
use FastFrame\Utility\ArrayHelper;
use FastFrame\Utility\NestedArrayHelper;
use Guzzle\Common\Exception\RuntimeException;
use josegonzalez\Dotenv\Loader;

/**
 * The role of this class is to be able to load the Kernels environment.
 *
 * This contains several resolvers to assist developers in setting up the environment
 *
 * @package FastFrame\Kernel
 */
class Environment
	implements \ArrayAccess
{
	/**
	 * Define the defaults
	 */
	const DEFAULT_MODE = 'development';

	/**
	 * Define the constants for keys that are used in the system
	 */
	const KEY_MODE = 'mode';
	const KEY_CONFIG_NAMESPACE = 'config_namespace';
	const KEY_ENV_CACHE_PATH = 'path_env_cache';
	const KEY_ENV_CACHE_ENABLED = 'cache_env_enabled';
	const KEY_ROOT_PATH = 'path_root';

	/**
	 * Define the configuration namespace
	 */
	const CONFIG_NAMESPACE = 'FastFrame\_Config';

	/**
	 * The path to the root of the Framework
	 *
	 * @var string
	 */
	private $rootPath;

	/**
	 * Composer ClassLoader
	 *
	 * @var ClassLoader
	 */
	private $autoloader;

	/**
	 * The environment from dotenv merged with the defaults
	 *
	 * @var array
	 */
	private $env;

	/**
	 * Whether or not this is a console request
	 *
	 * @var bool
	 */
	private $isConsole;

	public function __construct($rootPath, ClassLoader $autoloader = null)
	{
		$this->rootPath   = $rootPath;
		$this->autoloader = $autoloader;
		$this->isConsole  = PHP_SAPI === 'cli';
		$this->env        = $_ENV;
	}

	/**
	 * Loads the environment
	 *
	 * @param null|string|array $env
	 * @return array|mixed
	 */
	public function load($env = null)
	{
		if (is_string($env) || empty($env)) {
			if (!file_exists($env) && !file_exists($env = "{$this->rootPath}/.env")) {
				throw new RuntimeException("Missing .env file");
			}
			$loader = new Loader($env);
			$loader->parse();
			$env = $loader->toArray();
		}

		$env = $this->updateEnvironmentForMissingDefaults($env);

		isset($env['debug']) && define('DEBUG_MODE', true);

		$this->env = NestedArrayHelper::deepMerge($this->env, $env);

		return $this->env;
	}

	/**
	 * Sets whether or not this is a console vs web request
	 *
	 * This is designed to be used for testing of non console components
	 *
	 * @seam
	 * @param boolean $value
	 */
	public function setIsConsole($value)
	{
		$this->isConsole = $value;
	}

	/**
	 * Returns whether the system is running on the console
	 *
	 * @return bool
	 */
	public function isConsole()
	{
		return $this->isConsole;
	}

	/**
	 * Returns the autoloader
	 *
	 * This is used for those instances when you need to modify the autoloader during your configuration stage
	 *
	 * @return ClassLoader
	 */
	public function autoloader()
	{
		return $this->autoloader;
	}

	/**
	 * Returns the rootPath variable
	 *
	 * @return string
	 */
	public function rootPath()
	{
		return $this->rootPath;
	}

	/**
	 * Alternative to $this['key] to allow returning a default value
	 *
	 * @param string $key
	 * @param mixed  $alt
	 * @return null
	 */
	public function get($key, $alt = null)
	{
		return array_key_exists($key, $this->env) ? $this->env[$key] : $alt;
	}

	/**
	 * Return a list of all environment data
	 *
	 * @return array
	 */
	public function all()
	{
		return $this->env;
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->env);
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetGet($offset)
	{
		return isset($this->env[$offset]) ? $this->env[$offset]: null;
	}

	/**
	 * {@inheritdoc}
	 * @throws \RuntimeException Modification is not allowed
	 */
	public function offsetSet($offset, $value)
	{
		$this->env[$offset] = $value;
	}

	/**
	 * {@inheritdoc}
	 * @throws \RuntimeException Modification is not allowed
	 */
	public function offsetUnset($offset)
	{
		unset($this->env[$offset]);
	}

	/**
	 * Updates the environment to set the mode, config_namespace, and root.path if they are not set
	 *
	 * @param array  $env  The array of env data from dotenv
	 * @param string $mode The environment mode
	 * @return array
	 */
	protected function updateEnvironmentForMissingDefaults($env, $mode = null)
	{
		return array_merge(
			$env,
			[
				static::KEY_MODE             => ArrayHelper::keyValue($env, static::KEY_MODE, $mode ?: static::DEFAULT_MODE),
				static::KEY_CONFIG_NAMESPACE => ArrayHelper::keyValue($env, static::KEY_CONFIG_NAMESPACE, static::CONFIG_NAMESPACE),
				static::KEY_ROOT_PATH        => $this->rootPath
			]
		);
	}
}