<?php

// src/AppBundle/Command/CreateUserCommand.php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Code;


class ImportCodeCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {
        $this
            ->setName('app:import-code')
            ->setDescription('Import Codes.')
            ->setHelp("This command imports codes...")
        ;
    }

    
    /**
     * Import codes
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('app.doctrine_helper')->turncate(\AppBundle\Entity\Code::class);
        
//      $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $conn = $this->getContainer()->get('database_connection');
        
        if (($handle = fopen (__DIR__. '/kody.csv',"r")) !== FALSE) {
            
            $i = 0;    
            while (($data = fgetcsv($handle,null,';')) !== FALSE)  {

                $i++;
                if ( $i == 1 ) { // skip first row
                    continue;
                }
                
                $code = array(
                    'code' => $data[0], 
                    'streetName' => $data[1], 
                    'cityName' => $data[2], 
                    'provinceName' => $data[3], 
                    'regionName' => $data[4]);
                $conn->insert('code', $code);
                
//                $code = new Code();
//                $code->setCode($data[0]);
//                $code->setStreetName($data[1]);
//                $code->setCityName($data[2]);
//                $code->setProvinceName($data[3]);
//                $code->setRegionName($data[4]);
//                
//                $em->persist($code);
//                $em->flush();

            }
            
            fclose ($handle);
        }
    }
    
}