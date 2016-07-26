<?php

namespace TobiasOlry\TalklyBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TobiasOlry\TalklyBundle\DependencyInjection\SymfonyCompilerPass;

class TobiasOlryTalklyBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SymfonyCompilerPass());
    }

}