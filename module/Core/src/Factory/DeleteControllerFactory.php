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
        
        //command
        $this->command_class = (!empty($config['command_class'])) 
                ? $config['command_class'] : false;
        if (!$this->command_class) {
            return false;
        }
        
        //repository
        $this->repository_class = (!empty($config['repository_class'])) 
                ? $config['repository_class'] : false;
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

