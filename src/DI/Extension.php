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

		$applicationType = $containerBuilder->getByType(\Nette\Application\Application::class);
		$application = $containerBuilder->getDefinition($applicationType);
		$application->addSetup('?->onPresenter[] = ?', ['@self', [$presenterHook, 'onPresenter']]);
	}

}
