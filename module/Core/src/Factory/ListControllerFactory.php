<?php
namespace Core\Factory;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * List Controller Factory
 * @author bojan
 */
class ListControllerFactory implements AbstractFactoryInterface
{
    const TYPE = 'list';
    
    protected $repository_class;
    
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
        
        $controller_config = 
                (!empty($config['controller_config'][$requestedName])) 
                ? $config['controller_config'][$requestedName] : null;
        if (!$controller_config) {
            return false;
        }
        
        $type = (!empty($controller_config['type'])) 
                ? $controller_config['type'] : false;
        if (!$type || $type != self::TYPE) {
            return false;
        }
        
        $this->repository_class = 
                (!empty($controller_config['repository_class'])) 
                ? $controller_config['repository_class'] : null;
        if (!$this->repository_class) {
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
        $logger = $container->get('Core\Logger\MonologLogger');
        $repository = $container->get($this->repository_class);
        
        return new $requestedName($repository, $logger);
    }
}

