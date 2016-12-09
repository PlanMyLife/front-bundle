<?php
namespace PlanMyLife\FrontBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class watchAssetsCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('front:watch')

            // the short description shown while running "php bin/console list"
            ->setDescription('watch a path of the front')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command run watcher find in gulpfile")

            //Add folder to watch
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the path in the configuration file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $container = $this->getContainer();

        if( !$container->hasParameter('pml_front_generator.path') ) {
            return $output->writeln( 'Vous devez définir les dossiers à compiler dans le config.yml' );
        }

        //CHECK INSTALL
        if( !$this->command_exist('bundle') || !$this->command_exist('npm') || strpos(shell_exec('bundle check'), 'Install missing gems with `bundle install') ) {
            return $output->writeln( 'Attention, la compilation n\'est pas prête lancer la commande front:install.' );
        }

        $bundles = $container->getParameter('pml_front_generator.path');

        foreach ( $bundles as $bundle ) {
            if($bundle['name'] == $input->getArgument('name')) {
                $task = ' --path ' . $bundle['src'];
                passthru('gulp watch' . $task);
            }
        }
    }

    private function command_exist($cmd) {
        return (empty(shell_exec("which $cmd")) ? false : true);
    }
}