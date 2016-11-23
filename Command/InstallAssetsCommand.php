<?php
namespace PlanMyLife\FrontBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallAssetsCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('front:install')

            // the short description shown while running "php bin/console list"
            ->setDescription('Install the front')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command install every dependancies and compile every gulpfile find in folder")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $container = $this->getContainer();

        if( !$container->hasParameter('pml_front_generator.path') ) {
            return $output->writeln( 'Vous devez définir les dossiers à compiler dans le config.yml' );
        }

        //CHECK BUNDLER
        if( !$this->command_exist('bundle') ) {
            return $output->writeln( 'Attention, bundle(http://bundler.io/) est requis pour ce projet.' );
        }

        if( !$this->command_exist('npm') ) {
            return $output->writeln( 'Attention, npm(https://www.npmjs.com/) est requis pour ce projet.' );
        }

        $bundles = $container->getParameter('pml_front_generator.path');
    }

    private function command_exist($cmd) {
        return (empty(shell_exec("which $cmd")) ? false : true);
    }
}