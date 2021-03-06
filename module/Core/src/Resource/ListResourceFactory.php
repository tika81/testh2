<?php
namespace Core\Resource;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Http\PhpEnvironment\Request;
use Core\Resolver\DependencyResolver;
use Core\Builder\MapperBuilder;

/**
 * Description of ListResourceFacory
 *
 * @author TikaLT
 */
class ListResourceFactory implements AbstractFactoryInterface
{
    const TYPE = 'list';
    
    protected $config;
    
    /**
     * Can the factory create an instance for the service?
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
        $this->config = (!empty($global_config['resource_config'][$requestedName])) 
                ? $global_config['resource_config'][$requestedName] : null;
        if (!$this->config) {
            return false;
        }
        
        $type = (!empty($this->config['type'])) ? $this->config['type'] : false;
        if (!$type || $type != self::TYPE) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Create an object
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
        $logger = $container->get('Core\Logger\MonologLogger');
        $mapper_builder = new MapperBuilder($container, $logger, $config);
        $repository = $mapper_builder->createRepository();
        $request = new Request;
        $basic_args = [$request, $repository, $logger];
        
        $dependency_classes = (!empty($config['dependencies'])) 
                ? $config['dependencies'] : [];
        $dependency_resolver = new DependencyResolver(
                $container, 
                $dependency_classes
        );
        $dependencies = $dependency_resolver->resolveDependencies();
        $args = array_merge($basic_args, $dependencies);
        
        $reflector = new \ReflectionClass($requestedName);
        return $reflector->newInstanceArgs($args);
    }
}
