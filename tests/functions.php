<?php declare(strict_types = 1);

/**
 * @param \Nette\DI\Compiler $source
 * @param array<mixed> $config
 */
function createContainer(\Nette\DI\Compiler $source, array $config = []): \Nette\DI\Container
{
	$class = 'Container' . \md5((string) \lcg_value());
	$code = $source->addConfig($config)->setClassName($class)->compile();
	eval($code);

	return new $class();
}
