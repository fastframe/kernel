<?php

namespace FastFrame\Kernel;

use FastFrame\_Config\ProviderResolveTest;
use FastFrame\Kernel\Mocks\TestSubProviders;
use Interop\Container\ContainerInterface;
use org\bovigo\vfs\vfsStream;

class HasSubProvidersTest
	extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->vfs = vfsStream::setup(
			'auth', null,
			[
				'.env' => 'FASTFRAME_ENV=kakaw
				mode=testing'
			]);
	}

	public function buildEnvironment($env = null, $autoloader = null)
	{
		$envObj = new Environment($this->vfs->url('auth'), $autoloader);

		$envObj->load($env);

		return $envObj;
	}

	public function testRunProviderDefine()
	{
		$p = new TestSubProviders($this->buildEnvironment());
		$p->runProviderDefine($this->getMock(ContainerInterface::class));
	}

	public function testRunProviderModify()
	{
		$p = new TestSubProviders($this->buildEnvironment());
		$p->runProviderModify($this->getMock(ContainerInterface::class));
	}
}