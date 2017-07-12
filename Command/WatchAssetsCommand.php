<?php

namespace PlanMyLife\FrontBundle\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;

class WatchAssetsCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('front:watch')
            ->setDescription('Watch a path of the front')
            ->setHelp("This command run watcher find in gulpfile")
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the path in the configuration file'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        if (!$container->hasParameter('pml_front_generator.path')) {
            return $output->writeln('You must define the folders to be compiled in config.yml');
        }
        
        if (!$this->commandExist('bundle') ||
            !$this->commandExist('npm') ||
            strpos(shell_exec('bundle check'), 'Install missing gems with `bundle install')
        ) {
            return $output->writeln('Caution, the compilation is not ready to run the command front: install.');
        }

        $bundles = $container->getParameter('pml_front_generator.path');

        foreach ($bundles as $bundle) {
            if ($bundle['name'] === substr($input->getArgument('name'), 5, strlen($input->getArgument('name')))) {
                $task = sprintf(' --path %s', $bundle['src']);

                passthru(sprintf('gulp watch %s', $task));
            }
        }
    }
}
