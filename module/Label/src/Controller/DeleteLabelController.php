<?php
namespace Label\Controller;

use Core\Model\CommandInterface;
use Core\Model\RepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Psr\Log\LoggerInterface;
use Core\Resource\DeleteResourceInterface;
use Core\Model\EntityInterface;

/**
 * Delete Label Controller
 * @author bojan
 */
class DeleteLabelController extends AbstractActionController
{
    /**
     * @var DeleteResourceInterface
     */
    protected $resource;
    
    /**
     * @var LoggerInterface 
     */
    protected $logger;
    
    /**
     * @param CommandInterface $command
     * @param RepositoryInterface $repository
     * @param LoggerInterface $logger
     */
    public function __construct(
            DeleteResourceInterface $resource, 
            LoggerInterface $logger
    ) {
        $this->resource = $resource;
        $this->logger = $logger;
    }
    
    /**
     * Delete Action
     * @return ViewModel
     */
    public function deleteAction()
    {
        $id = intval($this->params()->fromRoute('id'));
        if (!$id) {
            $this->logger->error(
                    sprintf('[Line:%d] - Identifier \'id\' not found, file: %s',
                            __LINE__, __FILE__)
            );
            return $this->redirect()->toRoute('label');
        }
        
        $label = $this->resource->delete($id);
        if (is_string($label)) {
            return $this->redirect()->toRoute('label');
        } elseif (is_array($label)) {
            return new ViewModel($label);
        }
        
        return $this->redirect()->toRoute('label');
    }
    
}

