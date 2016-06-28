<?php

namespace TobiasOlry\TalklyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TobiasOlry\TalklyBundle\Entity\User;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class UserCreateCommand extends ContainerAwareCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('talkly:user:create');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $username = $io->ask('username');
        $password = $io->askHidden('password');

        $encoder = $this->getContainer()->get('security.password_encoder');
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $user = new User($username);
        $user->setPassword($encoder->encodePassword($user, $password));

        dump($user->getPassword());

        $entityManager->persist($user);
        $entityManager->flush();
    }
}