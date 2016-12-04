<?php
namespace Label\Controller;

use Core\Resource\WriteResourceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Psr\Log\LoggerInterface;
use Core\Model\EntityInterface;

/**
 * Write Label Controller
 * @author bojan
 */
class WriteLabelController extends AbstractActionController
{
    /**
     * @var WriteResourceInterface 
     */
    protected $resource;
    
    /**
     * @var LoggerInterface 
     */
    protected $logger;
    
    /**
     * @param WriteResourceInterface $resource
     * @param LoggerInterface $logger
     */
    public function __construct(
            WriteResourceInterface $resource,
            LoggerInterface $logger
    ) {
        $this->resource    = $resource;
        $this->logger = $logger;
    }
    
    /**
     * Add action
     * @return ViewModel
     */
    public function addAction()
    {
        $label = $this->resource->add();
        if ($label instanceof EntityInterface) {
            return $this->redirect()->toRoute('label/detail', ['id' => $label->id]);
        } elseif (is_array($label)) {
            return new ViewModel($label);
        }
    }
    
    /**
     * Edit action
     * @return ViewModel
     */
    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $this->logger->error(
                    sprintf('[Line:%d] - Identifier \'id\' not found, file: %s',
                            __LINE__, __FILE__)
            );
            return $this->redirect()->toRoute('label');
        }
        
        $label = $this->resource->edit($id);
        if (is_string($label)) {
            return $this->redirect()->toRoute('label');
        } elseif (is_array($label)) {
            return new ViewModel($label);
        } elseif ($label instanceof EntityInterface) {
            return $this->redirect()->toRoute(
                    'label/detail',
                    ['id' => $label->id]
            );
        }
    }
}
