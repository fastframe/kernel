<?php

namespace FastFrame\Kernel;

use FastFrame\_Config\ProviderResolveTest;
use FastFrame\Kernel\Mocks\TestSubProviders;
use Interop\Container\ContainerInterface;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class HasSubProvidersTest
	extends TestCase
{
	public function setUp(): void
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

		$a = new ProviderResolveTest();
		$p->add($a);

		$p->runProviderDefine($this->getMockBuilder(ContainerInterface::class)->getMock());

		self::assertTrue($a->define);
		self::assertFalse($a->modify);
	}

	public function testRunProviderModify()
	{
		$p = new TestSubProviders($this->buildEnvironment());

		$a = new ProviderResolveTest();
		$p->add($a);


		$p->runProviderModify($this->getMockBuilder(ContainerInterface::class)->getMock());
		self::assertFalse($a->define);
		self::assertTrue($a->modify);
	}
}
