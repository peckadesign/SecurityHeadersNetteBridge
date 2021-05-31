<?php declare(strict_types = 1);

namespace Pd\SecurityHeaders\DI;

final class HeadersSetup implements IOnPresenterListener
{

	private bool $headersSent = FALSE;

	private \Pd\SecurityHeaders\DI\IHeadersFactory $factory;

	private \Nette\Http\IResponse $response;


	public function __construct(\Pd\SecurityHeaders\DI\IHeadersFactory $factory, \Nette\Http\IResponse $response)
	{
		$this->factory = $factory;
		$this->response = $response;
	}


	public function onPresenter(\Nette\Application\Application $application, \Nette\Application\IPresenter $presenter): void
	{
		if (\PHP_SAPI === 'cli') {
			return;
		}

		if ($this->headersSent) {
			return;
		}

		foreach ($this->factory->getHeaders() as $header) {
			$this->response->addHeader($header->getName(), $header->getValue());
		}

		$this->headersSent = TRUE;
	}

}
