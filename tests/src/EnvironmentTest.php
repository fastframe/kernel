<?php

namespace FastFrame\Kernel;

use Composer\Autoload\ClassLoader;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class EnvironmentTest
	extends TestCase
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

	public function testRootPath()
	{
		$rootPath = $this->vfs->url('auth');
		self::assertEquals($rootPath, (new Environment($rootPath))->rootPath());
	}

	public function testAutoloaderReturnsClassLoader()
	{
		$autoloader = new ClassLoader();
		self::assertEquals($autoloader, (new Environment(null, $autoloader))->autoloader());
	}

	public function testIsConsole()
	{
		$env = $this->buildEnvironment();
		self::assertTrue($env->isConsole());
		$env->setIsConsole(false);
	}

	public function testSetIsConsole()
	{
		$env = $this->buildEnvironment();
		$env->setIsConsole(false);
		self::assertFalse($env->isConsole());
	}

	public function testGetReturnsValue()
	{
		$env = $this->buildEnvironment();
		self::assertEquals('kakaw', $env->get('FASTFRAME_ENV'));
	}

	public function testGetWithoutAltReturnsNull()
	{
		$env = $this->buildEnvironment();
		self::assertNull($env->get('FAST_ENV'));
	}

	public function testGetWithAltReturnsAlt()
	{
		$env = $this->buildEnvironment();
		self::assertEquals('ack', $env->get('FAST_ENV', 'ack'));
	}

	public function testLoadThrowsException()
	{
		$this->expectException(\RuntimeException::class);
		$env = new Environment(__DIR__);
		$env->load('poi');

	}

	public function testOffsetSet()
	{
		$env           = $this->buildEnvironment();
		$env['tester'] = 'woot';

		self::assertEquals('woot', $env['tester']);
	}

	public function testOffsetGet()
	{
		$env = $this->buildEnvironment();
		self::assertEmpty($env['tester']);
	}

	public function testOffsetExists()
	{
		$env = $this->buildEnvironment();
		self::assertFalse(isset($env['tester']));
		self::assertTrue(isset($env['FASTFRAME_ENV']));
	}

	public function testOffsetUnset()
	{
		$env = $this->buildEnvironment();
		unset($env['FASTFRAME_ENV']);
		self::assertEmpty($env['FASTFRAME_ENV']);
	}

	public function testAllReturnsAll()
	{
		$env = $this->buildEnvironment();

		$ex                     = $_ENV;
		$ex['FASTFRAME_ENV']    = 'kakaw';
		$ex['mode']             = 'testing';
		$ex['config_namespace'] = "FastFrame\\_Config";
		$ex['path_root']        = $this->vfs->url('auth');
		self::assertEquals($ex, $env->all());
	}
}