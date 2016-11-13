<?php
namespace Core\Logger;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
//use Monolog\Logger;

/**
 * Logger Factory
 * @author bojan
 */
class LoggerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param type $requestedName
     * @param array $options
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $log_config = $config['logger'];
        $name = (!empty($log_config['handlers']['default']['logger_name'])) 
                ? $log_config['handlers']['default']['logger_name'] : 'default';
        
        $handler = $log_config['handlers']['default']['name'];
        $path    = $log_config['handlers']['default']['args']['path'];
        $level   = $log_config['handlers']['default']['args']['level'];
        
        $log = new $requestedName($name);
        $log->pushHandler(new $handler($path, $level));
        
        return $log; 
    }
}
