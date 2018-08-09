<?php declare(strict_types = 1);

namespace Pd\SecurityHeaders\DI;

final class Extension extends \Nette\DI\CompilerExtension
{

	/**
	 * @var string
	 */
	private $presenterHook;


	public function loadConfiguration()
	{
		parent::loadConfiguration();

		$config = $this->getConfig();

		if ( ! isset($config['presenterHook'])) {
			throw new \InvalidArgumentException(\sprintf('Extension "%s" nemá nastavenou hodnotu "presenterHook"', $this->name));
		}

		$this->presenterHook = $config['presenterHook'];
	}


	public function beforeCompile()
	{
		$containerBuilder = $this->getContainerBuilder();

		$presenterHookType = $containerBuilder->getByType(\substr($this->presenterHook, 1));
		$presenterHook = $containerBuilder->getDefinition($presenterHookType);

		$applicationType = $containerBuilder->getByType(\Nette\Application\Application::class);
		$application = $containerBuilder->getDefinition($applicationType);
		$application->addSetup('?->onPresenter[] = ?', ['@self', [$presenterHook, 'onPresenter']]);
	}

}
