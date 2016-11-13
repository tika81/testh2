<?php
namespace Label\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Label\Model\LabelRepository;
use Zend\View\Model\ViewModel;
use Core\Logger\Logger;

/**
 * List Label Controller
 * @author bojan
 */
class ListLabelController extends AbstractActionController
{
    /**
     * Logger
     * @var type 
     */
    protected $logger;
    
    /**
     * @var LabelRepository 
     */
    protected $label_repository;
    
    /**
     * @param LabelRepository $label_repository
     * @param Logger $logger
     */
    public function __construct(LabelRepository $label_repository, Logger $logger) 
    {
        $this->label_repository = $label_repository;
        $this->logger = $logger;
    }
    
    /**
     * Index action
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'labels' => $this->label_repository->fetchAll(),
        ]);
    }
    
    /**
     * Label Detail action 
     * @return ViewModel
     */
    public function detailAction()
    {
        $id = $this->params()->fromRoute('id');
        
        try {
            $label = $this->label_repository->fetch($id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('label');
        }
        
        return new ViewModel([
            'label' => $label,
        ]);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
