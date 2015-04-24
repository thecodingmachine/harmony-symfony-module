<?php
namespace Harmony\Bundle\SymfonyBundle;


use Harmony\Services\FileService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HarmonyCompilerPass implements CompilerPassInterface {
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $definitions = $container->getDefinitions();

        $idToClassMap = [];

        foreach ($definitions as $id => $definition) {
            $alias = false;

            if ($container->hasAlias($id)) {
                $alias = true;
                // Use find to resolve aliases.
                $definition = $container->findDefinition($id);
            }

            $class = $definition->getClass();
            if (strpos($class, "%") === 0) {
                $classpointer = trim($class, '%');
                $class = $container->getParameterBag()->get($classpointer);
            }

            // Note: the list of class is definitely not complete....
            // And we have aliases that we may not want...

            $idToClassMap[$id] = [
                "alias" => $alias,
                "class" => $class
            ];
        }

        FileService::writePhpExportFile(__DIR__.'/../../../../generated/servicesMap.php',
            $idToClassMap,
            "Generated file containing the list of services available in Symfony container.");
    }
}
