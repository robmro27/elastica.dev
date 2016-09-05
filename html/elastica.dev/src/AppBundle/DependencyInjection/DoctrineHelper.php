<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;


/**
 * Description of DoctrineHelper
 *
 * @author rmroz
 */
class DoctrineHelper {
    
    
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }   
    
    /**
     * 
     * @param type $className
     */
    public function turncate( $className ) 
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        
        $cmd = $em->getClassMetadata($className);
        
        $connection = $em->getConnection();
        
        $dbPlatform = $connection->getDatabasePlatform();
        
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        
        $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
        
        $connection->executeUpdate($q);
        
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
    }
    
}
