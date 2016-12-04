<?php
namespace Core\Builder;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Psr\Log\LoggerInterface;
use Core\Model\RepositoryInterface;

/**
 * Repository Builder
 * @author TikaLT
 */
class RepositoryBuilder 
{
    /**
     * @var ContainerInterface 
     */
    protected $container; 
    
    /**
     * @var LoggerInterface 
     */
    protected $logger;
    
    /**
     * @var array
     */
    protected $config; 
    
    /**
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     * @param type $config
     */
    public function __construct(
            ContainerInterface $container,
            LoggerInterface $logger,
            $config = []
    ) {
        $this->container = $container;
        $this->config = $config;
        $this->logger = $logger;
    }
    
    /**
     * Creates Repository
     * @return RepositoryInterface
     */
    public function createRepository()
    {
        $config = $this->config;
        
        $db = $this->container->get(AdapterInterface::class);
        $sql = new Sql($db);
        $select = $sql->select($config['table_name']);
        $repository_class = $config['repository_class'];
        $hydrator = new ReflectionHydrator();
        $prototype = new $config['entity_class'];
        $result_set = new HydratingResultSet($hydrator, $prototype);
        
        return new $repository_class($sql, $select, $result_set, $this->logger);
    }
}
