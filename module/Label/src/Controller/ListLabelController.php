<?php
namespace Label\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Core\Model\RepositoryInterface;
use Zend\View\Model\ViewModel;
use Psr\Log\LoggerInterface;

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
     * @var RepositoryInterface 
     */
    protected $repository;
    
    /**
     * @param LabelRepository $repository
     * @param Logger $logger
     */
    public function __construct(
            RepositoryInterface $repository, 
            LoggerInterface $logger
    ) {
        $this->repository = $repository;
        $this->logger = $logger;
    }
    
    /**
     * Index action
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'labels' => $this->repository->fetchAll(),
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
            $label = $this->repository->fetch($id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('label');
        }
        
        return new ViewModel([
            'label' => $label,
        ]);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
