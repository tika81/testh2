<?php
namespace Core\Factory;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * Delete Controller Factory
 * @author bojan
 */
class DeleteControllerFactory implements AbstractFactoryInterface
{
    const TYPE = 'delete';
    
    protected $config;
    
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
        $config = (!empty($global_config['controller_config'][$requestedName])) 
                ? $global_config['controller_config'][$requestedName] : false;
        if (!$config) {
            return false;
        }
        
        $type = (!empty($config['type'])) ? $config['type'] : false;
        if (!$type || $type != self::TYPE) {
            return false;
        }
        
        $delete_resource_class = (!empty($config['delete_resource_class'])) 
                ? $config['delete_resource_class'] : false;
        if (!$delete_resource_class) {
            return false;
        }
        
        $this->config = $config;
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
        $config = $this->config;
        $args = [
            $container->get($config['delete_resource_class']), 
            $container->get('Core\Logger\MonologLogger'),
        ];
        
        $reflector = new \ReflectionClass($requestedName);
        return $reflector->newInstanceArgs($args);
    }
}

