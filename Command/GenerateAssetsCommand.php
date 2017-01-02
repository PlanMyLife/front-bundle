<?php

namespace PlanMyLife\FrontBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateAssetsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('front:generate')
            ->setDescription('Generate the front files')
            ->setHelp('This command generate the files and architecture too generate the front assets')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}