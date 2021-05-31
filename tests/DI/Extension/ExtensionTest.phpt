<?php declare(strict_types = 1);

namespace PdTests\SecurityHeaders\DI\Extension;

require __DIR__ . '/../../bootstrap.php';

final class ExtensionTest extends \Tester\TestCase
{

	public function testWithoutHook(): void
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


	public function testValidationExceptions(): void
	{
		$extension = new \Pd\SecurityHeaders\DI\Extension();

		$extension->getConfigSchema();

		$httpExtension = new \Nette\Bridges\HttpDI\HttpExtension();
		$applicationExtension = new \Nette\Bridges\ApplicationDI\ApplicationExtension();
		$routingExtension = new \Nette\Bridges\ApplicationDI\RoutingExtension();

		$compiler = new \Nette\DI\Compiler();
		$compiler->addExtension('test', $extension);
		$compiler->addExtension('http', $httpExtension);
		$compiler->addExtension('application', $applicationExtension);
		$compiler->addExtension('routing', $routingExtension);

		\Tester\Assert::exception(
			static function() use ($compiler): void {
				createContainer($compiler, [
					'services' => [['factory' => HeadersFactory::class]],
					'test' => ['presenterHook' => 'asd'],
				]);
			},
			\Nette\DI\InvalidConfigurationException::class,
			"Failed assertion 'presenterHook must be a class-string' for item 'test › presenterHook' with value 'asd'."
		);

		\Tester\Assert::exception(
			static function() use ($compiler): void {
				createContainer($compiler, [
					'services' => [['factory' => HeadersFactory::class]],
					'test' => ['presenterHook' => \stdClass::class],
				]);
			},
			\Nette\DI\InvalidConfigurationException::class,
			"Failed assertion 'Hook presenteru musí implementovat rozhraní \"Pd\SecurityHeaders\DI\IOnPresenterListener\"' for item 'test › presenterHook' with value 'stdClass'."
		);
	}

}

class HeadersFactory implements \Pd\SecurityHeaders\DI\IHeadersFactory {

	public function getHeaders(): array
	{

	}

}

(new ExtensionTest())->run();
