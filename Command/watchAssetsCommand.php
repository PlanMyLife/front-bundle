<?php
namespace PlanMyLife\FrontBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class watchAssetsCommand extends ContainerAwareCommand
{
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        if (!$container->hasParameter('pml_front_generator.path')) {
            return $output->writeln('You must define the folders to be compiled in config.yml');
        }
        
        if (!$this->command_exist('bundle') ||
            !$this->command_exist('npm') ||
            strpos(shell_exec('bundle check'), 'Install missing gems with `bundle install')
        ) {
            return $output->writeln('Caution, the compilation is not ready to run the command front: install.');
        }

        $bundles = $container->getParameter('pml_front_generator.path');

        foreach ($bundles as $bundle) {
            if ($bundle['name'] == $input->getArgument('name')) {
                $task = ' --path ' . $bundle['src'];

                passthru('gulp watch' . $task);
            }
        }
    }

    private function command_exist($cmd)
    {
        return (empty(shell_exec("which $cmd")) ? false : true);
    }
}