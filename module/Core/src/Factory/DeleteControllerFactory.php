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
    
    protected $command_class;
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
        
        $controller_config = (!empty($config['controller_config'][$requestedName])) 
                ? $config['controller_config'][$requestedName] : false;
        if (!$controller_config) {
            return false;
        }
        
        $type = (!empty($controller_config['type'])) ? $controller_config['type'] : false;
        if (!$type || $type != self::TYPE) {
            return false;
        }
        
        //command
        $this->command_class = (!empty($controller_config['command_class'])) 
                ? $controller_config['command_class'] : false;
        if (!$this->command_class) {
            return false;
        }
        
        //repository
        $this->repository_class = (!empty($controller_config['repository_class'])) 
                ? $controller_config['repository_class'] : false;
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
        $command = $container->get($this->command_class);
        $repository = $container->get($this->repository_class);
        $logger = $container->get('Core\Logger\MonologLogger');
        
        return new $requestedName($command, $repository, $logger);
    }
}

