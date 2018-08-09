<?php declare(strict_types = 1);

namespace PdTests\SecurityHeaders\DI\Extension;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
class ExtensionTest extends \Tester\TestCase
{

	public function testWithoutHook()
	{
		$extension = new \Pd\SecurityHeaders\DI\Extension();

		$extension->setConfig([]);

		$compiler = new \Nette\DI\Compiler();
		$compiler->addExtension('test', $extension);

		$cb = function () use ($compiler): void {
			createContainer($compiler);
		};
		\Tester\Assert::exception($cb, \InvalidArgumentException::class);
	}

}

(new ExtensionTest())->run();
