<?php
namespace Label\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Psr\Log\LoggerInterface;
use Core\Resource\ListResourceInterface;
use Core\Model\EntityInterface;

/**
 * List Label Controller
 * @author bojan
 */
class ListLabelController extends AbstractActionController
{
    /**
     * Logger
     * @var LoggerInterface 
     */
    protected $logger;
    
    /**
     * @var ListResourceInterface 
     */
    protected $resource;
    
    /**
     * @param LabelRepository $resource
     * @param Logger $logger
     */
    public function __construct(
            ListResourceInterface $resource, 
            LoggerInterface $logger
    ) {
        $this->resource = $resource;
        $this->logger = $logger;
    }
    
    /**
     * Index action
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'labels' => $this->resource->fetchAll(),
        ]);
    }
    
    /**
     * Label Detail action 
     * @return ViewModel
     */
    public function detailAction()
    {
        $id = $this->params()->fromRoute('id');
        $label = $this->resource->fetch($id);
        if (!$label instanceof EntityInterface) {
            return $this->redirect()->toRoute('label');
        }
        
        return new ViewModel([
            'label' => $label,
        ]);
    }
}
