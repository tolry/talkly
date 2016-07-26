<?php

namespace TobiasOlry\TalklyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author David Badura <d.a.badura@gmail.com>
 *
 * fix https://github.com/symfony/symfony/pull/19434
 */
class SymfonyCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('property_info')) {
            $container->getDefinition('serializer.normalizer.object')->addArgument(new Reference('property_info'));
        }
    }
}