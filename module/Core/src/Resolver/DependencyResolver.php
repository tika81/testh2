<?php
namespace Core\Resolver;

use Interop\Container\ContainerInterface;

/**
 * Dependency Resolver
 * @author TikaLT
 */
class DependencyResolver
{
    /**
     * @var ContainerInterface 
     */
    protected $container;
    
    /**
     * Dependency classes
     * @var array
     */
    protected $dependency_classes;
    
    /**
     * @param ContainerInterface $container
     */
    public function __construct(
            ContainerInterface $container, 
            $dependency_classes = []
    ) {
        $this->container = $container;
        $this->dependency_classes = $dependency_classes;
    }
    
    /**
     * Resolves dependencies
     * @param ContainerInterface $container
     * @param array $dependency_classes
     * @return array
     */
    public function resolveDependencies()
    {
        $dependencies = [];
        foreach ($this->dependency_classes as $dependency_class) {
            $dependency = $this->resolveDependency($dependency_class);
            if ($dependency) {
                $dependencies[] = $dependency;
            }
        }
        return $dependencies;
    }
    
    /**
     * Resolve dependency
     * @param ContainerInterface $container
     * @param string $dependency_class
     * @return dependency_class|boolean
     */
    private function resolveDependency($dependency_class = '')
    {
        if ($this->container->has($dependency_class)) {
            return $this->container->get($dependency_class);
        }
        
        if (class_exists($dependency_class)) {
            $refl = new \ReflectionClass($dependency_class);
            if ($refl->isInstantiable()) {
                return new $dependency_class;
            }
        }
        
        return false;
    }
}
