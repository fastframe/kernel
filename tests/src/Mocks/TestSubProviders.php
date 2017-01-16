<?php
namespace FastFrame\Kernel\Mocks;

use FastFrame\Kernel\HasSubProviders;

class TestSubProviders
{
	use HasSubProviders;

	public function __construct($env)
	{
		$this->providers = $this->initProviderList($env);
	}
}