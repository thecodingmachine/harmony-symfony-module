<?php
namespace Harmony\Bundle\SymfonyBundle;


use Harmony\Module\ContainerExplorerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SymfonyContainerExplorer implements ContainerExplorerInterface {
    private $container;
    private $servicesMap;

    /**
     * @param array $servicesMap
     */
    public function __construct(ContainerInterface $container, array $servicesMap) {
        $this->servicesMap = $servicesMap;
        $this->container = $container;
    }

    /**
     * Builds a container explorer from the default generated/servicesMap.php file.
     * @return SymfonyContainerExplorer
     */
    public static function build(ContainerInterface $container) {
        if (file_exists(__DIR__.'/../../../../generated/servicesMap.php')) {
            $servicesMap = require __DIR__.'/../../../../generated/servicesMap.php';
        } else {
            $servicesMap = null;
        }
        return new self($container, $servicesMap);
    }

    /**
     * Returns the name of the instances implementing `$type`.
     * Will scan only the main services, not the aliases.
     *
     * @return string[]
     */
    public function getInstancesByType($type)
    {
        $type = ltrim($type, '\\');
        $services = array();

        foreach ($this->servicesMap as $id => $desc) {
            if ($desc['alias'] == true) {
                continue;
            }
            if ($desc['class'] !== null) {
                if ($type == $desc['class'] || is_subclass_of($desc['class'], $type)) {
                    $services[] = $id;
                    continue;
                }
            } else {
                // no class, we must instantiate the class to find its type
                try {
                    $instance = $this->container->get($id);
                    if (is_object($instance)) {
                        if ($type == get_class($instance) || is_subclass_of($instance, $type)) {
                            $services[] = $id;
                            continue;
                        }
                    }
                } catch (\Exception $e) {
                    // No exceptions allowed to bubble up.
                }
            }
        }
        return $type;
    }
}
