<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $this
            ->setName('talkly:user:create')
            ->addArgument('username', null, '', null)
            ->addArgument('password', null,  '', null)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if (!$username = $input->hasArgument('username')) {
            $input->setArgument('username', $io->ask('username'));
        }

        if (!$password = $input->hasArgument('password')) {
            $input->setArgument('password', $io->askHidden('password'));
        }
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $encoder = $this->getContainer()->get('security.password_encoder');
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $user = new User($username);
        $user->setPassword($encoder->encodePassword($user, $password));

        $entityManager->persist($user);
        $entityManager->flush();

        $io->success('user created');
    }
}
