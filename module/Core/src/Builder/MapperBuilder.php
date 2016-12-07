<?php
namespace Core\Builder;

use Zend\Db\Adapter\AdapterInterface;
use Psr\Log\LoggerInterface;
use Interop\Container\ContainerInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Core\Model\CommandInterface;
use Core\Model\RepositoryInterface;

/**
 * Mapper Builder
 *
 * @author TikaLT
 */
class MapperBuilder 
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
     * Creates Command 
     * @return CommandInterface
     */
    public function createCommand()
    {
        $command_class = $this->config['command_class'];
        return new $command_class($this->createTableGateway(), $this->logger);
    }
    
    /**
     * Creates repository
     * @return RepositoryInterface
     */
    public function createRepository()
    {
        $repository_class = $this->config['repository_class'];
        return new $repository_class($this->createTableGateway(), $this->logger);
    }
    
    /**
     * Creates table gateway
     * @return TableGatewayInterface
     */
    private function createTableGateway()
    {
        $prototype = new $this->config['entity_class'];
        $table = $this->config['table_name'];
        $adapter = $this->container->get(AdapterInterface::class);
        $result_set_prototype = new ResultSet();
        $result_set_prototype->setArrayObjectPrototype($prototype);
        return new TableGateway($table, $adapter, null, $result_set_prototype);
    }
}
