<?php

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * Class MicroKernel
 */
class MicroKernel extends Kernel {
	use MicroKernelTrait;

	/**
	 * {@inheritdoc}
	 */
	public function getCacheDir() {
		return $this->rootDir . '/../storage/cache/' . $this->environment;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLogDir() {
		return $this->rootDir . '/../storage/logs';
	}

	/**
	 * @return array
	 */
	public function registerBundles() {
		$bundles = [
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Csa\Bundle\GuzzleBundle\CsaGuzzleBundle(),
			new Snc\RedisBundle\SncRedisBundle(),
			new Application\ApplicationBundle(),
		];

		if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
			$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
		}

		return $bundles;
	}

	/**
	 * @param RouteCollectionBuilder $routes
	 */
	protected function configureRoutes(RouteCollectionBuilder $routes) {}

	/**
	 * @param ContainerBuilder $c
	 * @param LoaderInterface $loader
	 */
	protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader) {
		$loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
	}
}
