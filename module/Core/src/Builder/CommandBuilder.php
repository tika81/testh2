<?php
namespace Core\Builder;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Psr\Log\LoggerInterface;
use Interop\Container\ContainerInterface;
use Core\Model\CommandInterface;

/**
 * Command Builder
 * @author TikaLT
 */
class CommandBuilder 
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
        $config = $this->config;
        $table_name = $config['table_name'];
        
        $db = $this->container->get(AdapterInterface::class);
        $sql = new Sql($db);
        $command_class = $config['command_class'];
        $insert = new Insert($table_name);
        $update = new Update($table_name);
        $delete = new Delete($table_name);
        
        return new $command_class($sql, $insert, $update, $delete, $this->logger);
    }
}
