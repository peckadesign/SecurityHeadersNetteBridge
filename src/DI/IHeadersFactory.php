<?php declare(strict_types = 1);

namespace Pd\SecurityHeaders\DI;

interface IHeadersFactory
{

	/**
	 * @return array|\Pd\SecurityHeaders\Headers\ContentSecurityPolicy\Header[]
	 */
	public function getHeaders(): array;

}
