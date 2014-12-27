<?php

namespace Transit\WebBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class ClonePrivateRepositoryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('transit:clone')
            ->setDescription('Clone repository')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the project')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $output->writeln('Cloning project ' . $name);

        $url = 'git@github.com:adamelso/archfizz.git';

        $fs = new Filesystem();

        $repositoriesDir = implode(DIRECTORY_SEPARATOR, [
            $this->getContainer()->getParameter('kernel.root_dir'),
            '..', 'var', 'repositories',
        ]);

        if (!$fs->exists($repositoriesDir)) {
            $fs->mkdir($repositoriesDir);
        }

        $cloneDir = implode(DIRECTORY_SEPARATOR, [$repositoriesDir, $name]);

        if ($fs->exists($cloneDir)) {
            $fs->remove($cloneDir);
        }

        $process = new Process(sprintf(
            'git clone --branch %s --depth 50 %s %s',
            'master', $url, $cloneDir
        ));

        $process->run(function ($type, $buffer) use ($output) {
            $output->writeln('  > '. $buffer);
        });

        if ($process->isSuccessful()) {
            $output->writeln('Deploying project ' . $name);
        } else {
            exit(1);
        }

        /**
         * @todo Check for Gemfile.lock
         */
        $process = new Process(sprintf(
            'bundle install --path vendor/bundle', $cloneDir
        ), $cloneDir);

        $process->run(function ($type, $buffer) use ($output) {
            $output->writeln('  > '. $buffer);
        });

        /**
         * @todo Decouple from Capistrano
         */
        $process = new Process(sprintf(
            'bundle exec cap deploy', $cloneDir
        ), $cloneDir);

        $process->run(function ($type, $buffer) use ($output) {
            $output->writeln('  > '. $buffer);
        });

        $output->writeln('Deployed!');
    }
}
