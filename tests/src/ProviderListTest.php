<?php

namespace FastFrame\Kernel;

use FastFrame\_Config\ProviderResolveTest;
use Interop\Container\ContainerInterface;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ProviderListTest
	extends TestCase
{
	private $autoloader;
	public function setUp()
	{
		$this->vfs = vfsStream::setup(
			'fs', null,
			[
				'auth' => [
					'.env' => 'FASTFRAME_ENV=kakaw
				mode=testing'
				],
				'al' => [
					'ProviderResolveTest.php' => file_get_contents(realpath(__DIR__). '/Mocks/ProviderResolveTest.php'),
					'ProviderResolveTest2.php' => file_get_contents(realpath(__DIR__). '/Mocks/ProviderResolveTest2.php')
				]
			]);
		$this->autoloader = function ($class) {
			switch ($class) {
				case 'FastFrame\_Config\ProviderResolveTest':
					$file = 'ProviderResolveTest';
					break;
				case 'FastFrame\_Config\Provider\ProviderResolveTest2':
					$file = 'ProviderResolveTest2';
					break;
			}
			if (isset($file)) {
				require_once realpath(__DIR__) . "/Mocks/{$file}.php";
			}
		};
		spl_autoload_register($this->autoloader);
	}

	public function tearDown()
	{
		spl_autoload_unregister($this->autoloader);
	}

	public function buildProviderList()
	{
		$env = new Environment($this->vfs->url('fs').'/auth/');
		$env->load();

		return new ProviderList($env);
	}

	public function testAppendThrowsExceptionOnInvalidClass()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->buildProviderList()->append("woot");
	}

	public function testAppendResolvesClass()
	{
		$pl = $this->buildProviderList();
		$pl->append("ProviderResolveTest");

		self::assertCount(1, $pl);
	}

	public function testAppendAcceptsObject()
	{
		$s = $this->getMockBuilder(Provider::class)->getMock();
		$pl = $this->buildProviderList();

		self::assertCount(0, $pl);
		$pl->append($s);
		self::assertCount(1, $pl);
		self::assertContains($s, $pl->all());
	}

	public function testPrependThrowsExceptionOnInvalidClass()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->buildProviderList()->prepend("woot");
	}

	public function testPrependResolvesClass()
	{
		$pl = $this->buildProviderList();
		$pl->prepend("ProviderResolveTest");

		self::assertCount(1, $pl);
	}

	public function testPrependAcceptsObject()
	{
		$s = $this->getMockBuilder(Provider::class)->getMock();
		$pl = $this->buildProviderList();

		self::assertCount(0, $pl);
		$pl->prepend($s);
		self::assertCount(1, $pl);
		self::assertContains($s, $pl->all());
	}

	public function testPrependAppendTogether()
	{
		$pl = $this->buildProviderList();
		$a1 = $this->getMockBuilder(Provider::class)->getMock();
		$a2 = $this->getMockBuilder(Provider::class)->getMock();
		$p1 = $this->getMockBuilder(Provider::class)->getMock();
		$p2 = $this->getMockBuilder(Provider::class)->getMock();
//		$a1 = $this->getMock(Provider::class);
//		$a2 = $this->getMock(Provider::class);
//		$p1 = $this->getMock(Provider::class);
//		$p2 = $this->getMock(Provider::class);

		$pl->append($a1);
		$pl->append($a2);
		$pl->prepend($p1);
			$pl->prepend($p2);

		self::assertSame([$p2,$p1, $a1,$a2], $pl->all());
	}

	public function testDefineCallsTheProviders()
	{
		$pl = $this->buildProviderList();

		$a = new ProviderResolveTest();
		$p = new ProviderResolveTest();

		$pl->append($a);
		$pl->append($p);
		$pl->define($this->getMockBuilder(ContainerInterface::class)->getMock());

		self::assertTrue($a->define);
		self::assertFalse($a->modify);
		self::assertTrue($p->define);
		self::assertFalse($p->modify);
	}

	public function testModifyCallsTheProviders()
	{
		$pl = $this->buildProviderList();

		$a = new ProviderResolveTest();
		$p = new ProviderResolveTest();

		$pl->append($a);
		$pl->append($p);

		$pl->modify($this->getMockBuilder(ContainerInterface::class)->getMock());

		self::assertTrue($a->modify);
		self::assertFalse($a->define);
		self::assertTrue($p->modify);
		self::assertFalse($p->define);
	}
}