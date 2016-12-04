<?php
namespace Core\Resource;

use Zend\Form\FormInterface;
use Core\Model\CommandInterface;
use Core\Resource\ListResourceInterface;
use Psr\Log\LoggerInterface;
use Zend\Stdlib\RequestInterface;

/**
 * Write Resource
 * @author TikaLT
 */
class WriteResource implements WriteResourceInterface
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
     * @var FormInterface
     */
    protected $form;
    
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
     * @param FormInterface $form
     * @param LoggerInterface $logger
     * @param ListResourceInterface $list_resource
     */
    public function __construct(
            RequestInterface $request,
            CommandInterface $command,
            FormInterface $form,
            LoggerInterface $logger,
            ListResourceInterface $list_resource
    ) {
        $this->request = $request;
        $this->command  = $command;
        $this->form     = $form;
        $this->logger   = $logger;
        $this->list_resource = $list_resource;
    }
    
    public function add()
    {
        return;
    }
    public function edit($id)
    {
        return;
    }
}
