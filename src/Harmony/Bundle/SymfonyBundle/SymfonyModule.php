<?php
namespace Harmony\Bundle\SymfonyBundle;

use Acclimate\Container\ContainerAcclimator;
use Harmony\Module\ContainerExplorerInterface;
use Harmony\Module\HarmonyModuleInterface;
use Interop\Container\ContainerInterface;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpKernel\Kernel;

class SymfonyModule implements HarmonyModuleInterface {

    private $kernel;
    private $acclimatedContainer;
    private $containerExplorer;

    /**
     * Creates the module from the Symfony Kernel.
     * If no Kernel is passed, one will be loaded from app/AppKernel.php
     *
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel = null) {
        if ($kernel !== null) {
            $this->kernel = $kernel;
        } else {
            $loader = require_once __DIR__.'/../../../../../../../app/bootstrap.php.cache';
            Debug::enable();

            require_once __DIR__.'/../../../../../../../app/AppKernel.php';

            $this->kernel = new \AppKernel('dev', true);
            $this->kernel->loadClassCache();
        }
        $this->kernel->boot();

        $acclimate = new ContainerAcclimator();
        $this->acclimatedContainer = $acclimate->acclimate($this->kernel->getContainer());
    }

    /**
     * You can return a container if the module provides one.
     *
     * It will be chained to the application's root container.
     *
     * @param ContainerInterface $rootContainer
     * @return ContainerInterface|null
     */
    public function getContainer(ContainerInterface $rootContainer)
    {
        return $this->acclimatedContainer;
    }

    /**
     * Returns a class that can be used to explore a container
     *
     * @return ContainerExplorerInterface|null
     */
    public function getContainerExplorer()
    {
        if ($this->containerExplorer === null) {
            $this->containerExplorer = SymfonyContainerExplorer::build($this->kernel->getContainer());
        }
        return $this->containerExplorer;
    }
}