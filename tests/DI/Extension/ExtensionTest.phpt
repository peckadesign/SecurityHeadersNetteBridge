<?php declare(strict_types = 1);

namespace PdTests\SecurityHeaders\DI\Extension;

require __DIR__ . '/../../bootstrap.php';

final class ExtensionTest extends \Tester\TestCase
{

	public function testWithoutHook()
	{
		$extension = new \Pd\SecurityHeaders\DI\Extension();

		$extension->setConfig([]);

		$httpExtension = new \Nette\Bridges\HttpDI\HttpExtension();
		$applicationExtension = new \Nette\Bridges\ApplicationDI\ApplicationExtension();
		$routingExtension = new \Nette\Bridges\ApplicationDI\RoutingExtension();

		$compiler = new \Nette\DI\Compiler();
		$compiler->addExtension('test', $extension);
		$compiler->addExtension('http', $httpExtension);
		$compiler->addExtension('application', $applicationExtension);
		$compiler->addExtension('routing', $routingExtension);

		$container = createContainer($compiler, ['services' => [['factory' => HeadersFactory::class]]]);

		$listener = $container->getByType(\Pd\SecurityHeaders\DI\IOnPresenterListener::class);
		\Tester\Assert::equal(\Pd\SecurityHeaders\DI\HeadersSetup::class, \get_class($listener));
	}

}

class HeadersFactory implements \Pd\SecurityHeaders\DI\IHeadersFactory {

	public function getHeaders(): array
	{

	}

}

(new ExtensionTest())->run();
