<?php

namespace TobiasOlry\TalklyBundle\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class NtlmFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.ntlm.' . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('talkly.security.authentication.ntlm_provider'))
            ->replaceArgument(0, new Reference($userProvider));

        $container->setDefinition(
            $listenerId = ('security.authentication.listener.ntlm.' . $id),
            new DefinitionDecorator('talkly.security.authentication.ntlm_listener')
        );

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'ntlm';
    }

    public function addConfiguration(NodeDefinition $node)
    {
    }
}