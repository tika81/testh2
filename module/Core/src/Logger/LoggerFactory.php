<?php
namespace Core\Logger;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * Logger Factory
 * @author bojan
 */
class LoggerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $log_config = $config['logger'];
        $name = (!empty($log_config['name'])) ? $log_config['name'] : 'default';
        
        $handler = $log_config['handler'];
        $path    = $log_config['args']['path'];
        $level   = $log_config['args']['level'];
        
        $log = new $requestedName($name);
        $log->pushHandler(new $handler($path, $level));
        
        return $log; 
    }
}
