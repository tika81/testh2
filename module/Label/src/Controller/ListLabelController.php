<?php
namespace Label\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Label\Model\LabelRepository;
use Zend\View\Model\ViewModel;

/**
 * List Label Controller
 * @author bojan
 */
class ListLabelController extends AbstractActionController
{
    /**
     * @var LabelRepository
     */
    private $label_repository;
    
    public function __construct(LabelRepository $label_repository) 
    {
        $this->label_repository = $label_repository;
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
