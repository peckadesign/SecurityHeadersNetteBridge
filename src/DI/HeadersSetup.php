<?php declare(strict_types = 1);

namespace Pd\SecurityHeaders\DI;

final class HeadersSetup implements IOnPresenterListener
{
	/**
	 * @var bool
	 */
	private $headersSent = FALSE;

	/**
	 * @var IHeadersFactory
	 */
	private $factory;

	/**
	 * @var \Nette\Http\IResponse
	 */
	private $response;


	public function __construct(IHeadersFactory $factory, \Nette\Http\IResponse $response)
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
