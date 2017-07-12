<?php
namespace PlanMyLife\FrontBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class InstallAssetsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('front:install')
             ->setDescription('Install the front')
             ->setHelp('This command install every dependencies and compile every gulpfile find in folder')
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

        if (!$this->commandExist('bundle')) {
            return $output->writeln('Bundle (http://bundler.io/) is required for this project.');
        }

        if (!$this->commandExist('npm')) {
            return $output->writeln('Npm (https://www.npmjs.com/) is required for this project.');
        }

        if (strpos(shell_exec('bundle check'), 'Install missing gems with `bundle install')) {
            $output->writeln('The ruby ​​dependencies are not up to date, bundle execution install');
            passthru('bundle install --path=./.vendor/bundles');
        }

        $output->writeln('Check and install npm missing dependencies');
        passthru('npm install');

        $output->writeln('Cleaning...');
        passthru('gulp clean');

        $bundles = $container->getParameter('pml_front_generator.path');

        foreach ($bundles as $bundle) {
            $task = ' --path ' . $bundle['src'];
            $task .= ' --name ' . $bundle['name'];

            passthru('gulp build' . $task);
        }
    }

    /**
     * @param $cmd
     *
     * @return bool
     */
    protected function commandExist($cmd)
    {
        return (empty(shell_exec("which $cmd")) ? false : true);
    }
}
