<?php declare(strict_types = 1);

namespace Pd\SecurityHeaders\DI;

final class Extension extends \Nette\DI\CompilerExtension
{

	public function getConfigSchema(): \Nette\Schema\Schema
	{
		return \Nette\Schema\Expect::structure([
			'presenterHook' => \Nette\Schema\Expect::string(\Pd\SecurityHeaders\DI\HeadersSetup::class)
				->assert(static function (string $value): bool {
					return \class_exists($value);
				}, 'presenterHook must be a class-string')
				->assert(static function (string $value): bool {
					/** @var class-string $value */
					$presenterHookReflection = new \ReflectionClass($value);

					return $presenterHookReflection->implementsInterface(IOnPresenterListener::class);
				}, \sprintf('Hook presenteru musí implementovat rozhraní "%s"', IOnPresenterListener::class)),
		]);
	}


	public function beforeCompile(): void
	{
		/** @var \stdClass $config */
		$config = $this->getConfig();
		$containerBuilder = $this->getContainerBuilder();

		$presenterHook = $containerBuilder
			->addDefinition($this->prefix('presenterHook'))
			->setFactory($config->presenterHook)
		;

		/** @var \Nette\DI\Definitions\ServiceDefinition $application */
		$application = $containerBuilder->getDefinitionByType(\Nette\Application\Application::class);
		$application->addSetup('?->onPresenter[] = ?', ['@self', [$presenterHook, 'onPresenter']]);
	}

}
