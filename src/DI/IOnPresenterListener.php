<?php declare(strict_types = 1);

namespace Pd\SecurityHeaders\DI;

interface IOnPresenterListener
{

	public function onPresenter(\Nette\Application\Application $application, \Nette\Application\IPresenter $presenter): void;

}
