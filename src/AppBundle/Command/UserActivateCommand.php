<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
class UserActivateCommand extends ContainerAwareCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('talkly:user:activate')
            ->addArgument('username', null, '', null)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if (!$username = $input->getArgument('username')) {
            $input->setArgument('username', $io->ask('username'));
        }
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $repository = $entityManager->getRepository(User::class);

        $username = $input->getArgument('username');
        $user = $repository->findOneByUsername($username);

        if (! $user) {
            throw new \Exception("unknown user $username");
        }

        if ($user->isActive()) {
            $io->error("user $username is already active");
            return;
        }

        $user->activate();
        $entityManager->flush();

        $io->success("user $username sucessfully activated");
    }
}
