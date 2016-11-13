<?php
namespace Core\Factory;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Hydrator\Reflection as ReflectionHydrator;

/**
 * Repository Factory
 * @author bojan
 */
class RepositoryFactory implements AbstractFactoryInterface
{
    const TYPE = 'repository';

    protected $entity_class;
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
        $config = $container->get('Config');
        
        $mapper_config = (!empty($config['mapper_config'][$requestedName])) 
                ? $config['mapper_config'][$requestedName] : null;
        if (!$mapper_config) {
            return false;
        }
        
        $type = (!empty($mapper_config['type'])) ? $mapper_config['type'] : false;
        if (!$type || $type != self::TYPE) {
            return false;
        }
        
        $this->entity_class = (!empty($mapper_config['entity_class'])) 
                ? $mapper_config['entity_class'] : false;
        if (!$this->entity_class) {
            return false;
        }
        
        $this->table_name = (!empty($mapper_config['table_name'])) 
                ? $mapper_config['table_name'] : false;
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
        $select = $sql->select($this->table_name);
        
        $hydrator = new ReflectionHydrator();
        $prototype = new $this->entity_class;
        $result_set = new HydratingResultSet($hydrator, $prototype);
        
        return new $requestedName(
                $sql,
                $select,
                $result_set
        );
    }
}

