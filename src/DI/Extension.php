<?php declare(strict_types = 1);

namespace Pd\SecurityHeaders\DI;

final class Extension extends \Nette\DI\CompilerExtension
{

	/**
	 * @var class-string
	 */
	private $presenterHook = HeadersSetup::class;


	public function loadConfiguration()
	{
		parent::loadConfiguration();

		/** @var array<mixed> $config */
		$config = $this->getConfig();

		if (isset($config['presenterHook'])) {
			$this->presenterHook = $config['presenterHook'];
		}

		$presenterHookReflection = new \ReflectionClass($this->presenterHook);

		if ( ! $presenterHookReflection->implementsInterface(IOnPresenterListener::class)) {
			throw new \RuntimeException(\sprintf('Hook presenteru musí implementovat rozhraní "%s"', IOnPresenterListener::class));
		}
	}


	public function beforeCompile()
	{
		$containerBuilder = $this->getContainerBuilder();

		$presenterHook = $containerBuilder
			->addDefinition($this->prefix('presenterHook'))
			->setFactory($this->presenterHook)
		;

		/** @var \Nette\DI\Definitions\ServiceDefinition $application */
		$application = $containerBuilder->getDefinitionByType(\Nette\Application\Application::class);
		$application->addSetup('?->onPresenter[] = ?', ['@self', [$presenterHook, 'onPresenter']]);
	}

}
