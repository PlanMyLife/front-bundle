<?php

namespace PlanMyLife\FrontBundle\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class GenerateAssetsCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('front:generate')
            ->setDescription('Generate the front files')
            ->setHelp('This command generate the files and architecture too generate the front assets')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $kernel = $container->get('kernel');

        $pmlBundlePath = $kernel->locateResource('@PlanMyLifeFrontBundle');
        $rootPath = $kernel->getRootDir();

        $output->writeln('--- Check if Front Generator is installed ---');

        if ( !file_exists($rootPath . '/../common-design/') ) {
            $output->writeln('--- Paste Front Generator on Root ---');
            passthru('sudo cp -a ' . $pmlBundlePath . 'FrontArchitecture/theGenerator/. ' . $rootPath . '/../');
            $this->xcopy($pmlBundlePath . 'FrontArchitecture/theGenerator/', $rootPath . '/../');
        } else {
            $output->writeln('--- The Generator is installed ---');
        }

        $output->writeln('--- Check if every bundle in your config has the front stuff installed ---');

        $bundles = $container->getParameter('pml_front_generator.path');
        foreach ($bundles as $bundle) {
            $folder = $rootPath . '/../' . $bundle['src'];

            if ( !file_exists($folder . 'scss/') ) {
                $output->writeln('--- folder scss not exist, let\'s create it ---');
                mkdir($folder . 'scss/', 0775);
            }

            if ( !file_exists($folder . 'scss/' . $bundle['name'] . '.scss') ) {
                $output->writeln('--- No ' . $bundle['name'] . '.scss detected, generate a default one ---');
                copy($pmlBundlePath . 'FrontArchitecture/defaultFiles/styles_default.scss', $folder . 'scss/' . $bundle['name'] . '.scss');
            }

            if ( !file_exists($folder . 'js/') ) {
                $output->writeln('--- folder js not exist, let\'s create it ---');
                mkdir($folder . 'js/', 0775);
            }

            if ( !file_exists($folder . 'js/generic') ) {
                $output->writeln('--- folder js/generic not exist, let\'s create it ---');
                mkdir($folder . 'js/generic', 0775);
            }

            if ( !file_exists($folder . 'js/generic/' . $bundle['name'] . '.js') ) {
                $output->writeln('--- No ' . $bundle['name'] . '.js detected, generate a default one ---');
                copy($pmlBundlePath . 'FrontArchitecture/defaultFiles/js/generic_default.js', $folder . 'js/generic/' . $bundle['name'] . '.js');
            }

            if ( !file_exists($folder . 'js/generic/inc/') ) {
                $output->writeln('--- No js/generic/inc folder detected, generate a default one ---');
                mkdir($folder . 'js/generic/inc', 0775);
                $this->xcopy($pmlBundlePath . 'FrontArchitecture/defaultFiles/js/inc', $folder . 'js/generic/inc');
            }

            if ( !file_exists($folder . 'js/generic/libs/') ) {
                $output->writeln('--- No js/generic/libs folder detected, generate a default one ---');
                mkdir($folder . 'js/generic/libs', 0775);
                $this->xcopy($pmlBundlePath . 'FrontArchitecture/defaultFiles/js/libs', $folder . 'js/generic/libs');
            }

            if ( !file_exists($folder . 'js/pages') ) {
                $output->writeln('--- folder js/pages not exist, let\'s create it ---');
                mkdir($folder . 'js/pages', 0775);
            }
        }

        $output->writeln('-----------------------------------');
        $output->writeln('Your projet is ready to be compiled');
        $output->writeln('run the install command : console front:install');
    }
}
