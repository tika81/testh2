<?php
namespace Core\Factory;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * Write Controller Factory
 * @author bojan
 */
class WriteControllerFactory implements AbstractFactoryInterface
{
    const TYPE = 'write';
    
    protected $command_class;
    protected $form_class;
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
        
        //config
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
        
        //form
        $this->form_class = (!empty($controller_config['form_class'])) 
                ? $controller_config['form_class'] : false;
        if (!$this->form_class) {
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
        $form_manager = $container->get('FormElementManager');
        return new $requestedName(
                $container->get($this->command_class),
                $form_manager->get($this->form_class),
                $container->get($this->repository_class)
        );
    }
}

