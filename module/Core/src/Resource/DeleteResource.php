<?php
namespace Core\Resource;

use Core\Model\CommandInterface;
use Core\Resource\ListResourceInterface;
use Psr\Log\LoggerInterface;
use Zend\Stdlib\RequestInterface;

/**
 * Delete Resource
 *
 * @author TikaLT
 */
class DeleteResource implements DeleteResourceInterface
{
    /**
     * @var RequestInterface 
     */
    protected $request;
    
    /**
     * @var CommandInterface 
     */
    protected $command;
    
    /**
     * Logger
     * @var LoggerInterface 
     */
    protected $logger;
    
    /**
     * @var ListResourceInterface
     */
    protected $list_resource;
    
    /**
     * @param RequestInterface $request
     * @param CommandInterface $command
     * @param LoggerInterface $logger
     * @param ListResourceInterface $list_resource
     */
    public function __construct(
            RequestInterface $request,
            CommandInterface $command,
            LoggerInterface $logger,
            ListResourceInterface $list_resource
    ) {
        $this->request = $request;
        $this->command  = $command;
        $this->logger   = $logger;
        $this->list_resource = $list_resource;
    }
    
    public function delete($id) 
    {
        return;
    }
}
