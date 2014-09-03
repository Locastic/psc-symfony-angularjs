<?php
namespace Acme\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\BlogBundle\Entity\Page;

class LoadDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('load:data')
            ->setDescription('Adding dummy data...')
           ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        for($i=0; $i<50; $i++) {
            $page = new Page();
            $page->setTitle('Some title '. rand(1,10000));
            $page->setBody('Some Body yea '. rand(1,10000));

            $em->persist($page);
            $em->flush();
        }
    }
}