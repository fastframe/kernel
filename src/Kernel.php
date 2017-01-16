<?php

/**
 * @file
 * Contains \FastFrame\Kernel\Kernel
 */

namespace FastFrame\Kernel;

/**
 * Contract for Kernel's
 *
 * @package FastFrame\Kernel
 */
interface Kernel
{
	/**
	 * Returns the environment used by the kernel
	 *
	 * @return Environment
	 */
	public function env();

	/**
	 * Loads the given kernel type and returns it
	 *
	 * @param $type
	 * @return Kernel
	 */
	public function load($type);

	/**
	 * Executes the kernel, and emits a response
	 *
	 * @return void
	 */
	public function run($type = null);
}