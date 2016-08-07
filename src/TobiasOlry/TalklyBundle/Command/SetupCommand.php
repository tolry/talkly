<?php

namespace TobiasOlry\TalklyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class SetupCommand extends ContainerAwareCommand
{
    const ENV_PROD = 'production';
    const ENV_DEV = 'development';

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('talkly:setup')
            ->addArgument('environment', InputArgument::OPTIONAL);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if (!$input->getArgument('environment')) {
            $env = $io->choice('Environment?', [
                self::ENV_PROD,
                self::ENV_DEV
            ], self::ENV_PROD);

            $input->setArgument('environment', $env);
        }
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $env = $input->getArgument('environment');

        $io = new SymfonyStyle($input, $output);

        // todo check requirements
        $this->setupConfig();
        $this->setupDatabase($io, $env);
        $this->setupAssets($io, $env);
        $this->setupSecurity($io);

        if ($env === self::ENV_DEV) {
            $this->setupDevelopment($io);
        }

        if ($this->getContainer()->getParameter('security_type') === 'db') {
            $this->setupUser($io);
        }

        if ($env === self::ENV_PROD) {
            $this->setupProduction($io);
        }

        $io->success('finish! \o/');
    }

    private function setupConfig()
    {
        $filesystem = new Filesystem();

        if (!$filesystem->exists($this->getRoot() . '/app/config/local.yml')) {
            $filesystem->copy(
                $this->getRoot() . '/app/config/local.dist.yml',
                $this->getRoot() . '/app/config/local.yml'
            );
        }
    }

    /**
     * @param OutputStyle $io
     * @param string $env
     */
    private function setupDatabase(OutputStyle $io, $env)
    {
        $io->section('Setting up database');

        $dbal = $this->getContainer()->get('doctrine.dbal.default_connection');

        try {
            $dbal->connect();
        } catch (\Exception $e) {
            throw new \RuntimeException('Database Server Connection failed! Please check your app/config/parameters.yml');
        }

        $database = $dbal->getDatabase();

        $hasDatabase = $this->hasDatabase($database);

        if ($hasDatabase && $env === self::ENV_DEV && $io->confirm(sprintf('Drop "%s" database?', $database))
        ) {
            $this->executeSymfonyCommand($io, 'doctrine:database:drop --force');
            $hasDatabase = false;
        }

        if (!$hasDatabase) {
            if (!$io->confirm(sprintf('Create "%s" database?', $database))) {
                throw new \RuntimeException('Database not found! Please check your app/config/parameters.yml');
            }

            $this->executeSymfonyCommand($io, 'doctrine:database:create');
        }

        $this->executeSymfonyCommand($io, 'doctrine:migration:migrate -n');

        $io->success('Database');
    }

    /**
     * @param string $database
     * @return bool
     */
    private function hasDatabase($database)
    {
        $dbal = $this->getContainer()->get('doctrine.dbal.default_connection');

        foreach ($dbal->fetchAll('show databases') as $row) {
            if ($row['Database'] == $database) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param OutputStyle $io
     * @param string $env
     */
    private function setupAssets(OutputStyle $io, $env)
    {
        $io->section('Install assets');

        $io->caution('this may take few minutes...');

        // todo check node version & use nvm
        // todo node env

        $command = 'npm install';

        $io->note('Run: ' . $command);

        $process = new Process($command);
        $process->setTimeout(300);
        $process->setWorkingDirectory(realpath(__DIR__ . '/../../../../'));

        $process->start();

        while ($process->isRunning()) {
            $bar = $io->createProgressBar(10);
            $bar->setFormat(' installation...  [%bar%] please wait!');
            //$bar->setBarWidth(100);

            for ($i = 0; $i < 10; $i++) {
                $bar->advance();

                if (!$process->isRunning()) {
                    break 2;
                }

                usleep(mt_rand(10000, 500000));
            }

            $bar->finish();
        }

        $io->newLine(2);

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf('command "%s" failed!', $command));
        }

        $io->success('Assets');
    }

    /**
     * @param OutputStyle $io
     */
    private function setupUser(OutputStyle $io)
    {
        $io->section('Create user');

        if (!$io->confirm('do you want create a user?')) {
            return;
        }

        $username = $io->ask('username');
        $password = $io->askHidden('password');

        $this->executeSymfonyCommand($io, sprintf(
            'talkly:user:create %s %s',
            ProcessUtils::escapeArgument($username),
            ProcessUtils::escapeArgument($password)
        ));
    }

    /**
     * @param OutputStyle $io
     */
    private function setupProduction(OutputStyle $io)
    {
        try {
            $this->executeSymfonyCommand($io, 'rm web/app_dev.php web/index_dev.php');
        } catch (\Exception $e) {
            // do nothing
        }

        $this->executeSymfonyCommand($io, 'cache:clear --env=prod');

        if ($io->confirm('clean-up? (remove node_modules & bower_components)')) {
            $this->executeCommand($io, 'rm -Rf node_modules bower_components');
        }
    }

    /**
     * @param OutputStyle $io
     */
    private function setupDevelopment(OutputStyle $io)
    {
        if ($io->confirm('load fixtures?')) {
            $this->executeSymfonyCommand($io, 'hautelook_alice:doctrine:fixtures:load');
        }
    }

    /**
     * @param OutputStyle $io
     */
    private function setupSecurity(OutputStyle $io)
    {
        $io->section('Security');

        $folder = __DIR__ . '/../../../../var/jwt';
        $filesystem = new Filesystem();

        if (!$filesystem->exists($folder . '/private.pem')) {

            $filesystem->mkdir($folder);

            $this->executeCommand(
                $io,
                sprintf('openssl genrsa -passout pass:talkly -out %s/private.pem -aes256 4096', $folder)
            );

            $this->executeCommand(
                $io,
                sprintf('openssl rsa -pubout -in %1$s/private.pem -passin pass:talkly -out %1$s/public.pem', $folder)
            );
        }

        $io->success('Security');
    }

    /**
     * @param OutputStyle $io
     * @param string $command
     */
    private function executeSymfonyCommand(OutputStyle $io, $command)
    {
        $this->executeCommand($io, 'bin/console ' . $command);
    }

    /**
     * @param OutputStyle $io
     * @param string $command
     */
    private function executeCommand(OutputStyle $io, $command)
    {
        $process = new Process($command);
        $process->setTimeout(300);
        $process->setWorkingDirectory($this->getRoot());

        $io->note('Run: ' . $command);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf('command "%s" failed!', $command));
        }
    }

    /**
     * @return string
     */
    private function getRoot()
    {
        return realpath(__DIR__ . '/../../../../');
    }
}