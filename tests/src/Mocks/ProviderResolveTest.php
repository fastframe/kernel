<?php
namespace FastFrame\_Config;
use Interop\Container\ContainerInterface;

class ProviderResolveTest
	implements \FastFrame\Kernel\Provider
{
	public $define = false;
	public $modify = false;
	public function define(ContainerInterface $container)
	{
		$this->define = true;
	}

	public function modify(ContainerInterface $container)
	{
		$this->modify = true;
	}

}
