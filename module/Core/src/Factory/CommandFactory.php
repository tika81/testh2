<?php
namespace Core\Factory;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

/**
 * Command Factory
 * @author bojan
 */
class CommandFactory implements AbstractFactoryInterface
{
    const TYPE = 'command';
    
//    protected $entity_class;
    protected $table_name;
    
    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (!$container->get('Config') || !$container->get('EventManager')) {
            return false;
        }
        
        $global_config = $container->get('Config');
        
        $config = (!empty($global_config['mapper_config'][$requestedName])) 
                ? $global_config['mapper_config'][$requestedName] : false;
        if (!$config) {
            return false;
        }
        
        $type = (!empty($config['type'])) ? $config['type'] : false;
        if (!$type || $type != self::TYPE) {
            return false;
        }
        
        $this->table_name = (!empty($config['table_name'])) 
                ? $config['table_name'] : false;
        if (!$this->table_name) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(
            ContainerInterface $container, 
            $requestedName, 
            array $options = null
    ) {
        $db = $container->get(AdapterInterface::class);
        $sql = new Sql($db);
        $insert = new Insert($this->table_name);
        $update = new Update($this->table_name);
        $delete = new Delete($this->table_name);
        $logger = $container->get('Core\Logger\MonologLogger');
        
        return new $requestedName($sql, $insert, $update, $delete, $logger);
    }
}

