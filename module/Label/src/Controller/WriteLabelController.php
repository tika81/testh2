<?php
namespace Label\Controller;

use Label\Form\LabelForm;
use Label\Model\LabelCommand;
use Label\Model\LabelRepository;
use InvalidArgumentException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Psr\Log\LoggerInterface;

/**
 * Write Label Controller
 * @author bojan
 */
class WriteLabelController extends AbstractActionController
{
    /**
     * @var LabelCommand 
     */
    private $command;
    
    /**
     * @var LabelForm
     */
    private $form;
    
    /**
     * @var LabelRepository
     */
    private $repository;
    
    /**
     * Logger
     * @var LoggerInterface 
     */
    protected $logger;

    /**
     * @param LabelCommand $command
     * @param LabelForm $form
     * @param LabelRepository $repository
     * @param LoggerInterface $logger
     */
    public function __construct(
            LabelCommand $command, 
            LabelForm $form, 
            LabelRepository $repository,
            LoggerInterface $logger
    ) {
        $this->command    = $command;
        $this->form       = $form;
        $this->repository = $repository;
        $this->logger = $logger;
    }
    
    /**
     * Add action
     * @return ViewModel
     */
    public function addAction()
    {
        $request   = $this->getRequest();
        $view_model = new ViewModel(['form' => $this->form]);
        
        if (!$request->isPost()) {
            return $view_model;
        }
        
        $this->form->setData($request->getPost());
        
        if (!$this->form->isValid()) {
            return $view_model;
        }
        
        $label = $this->form->getData();
        
        try {
            $inserted_label = $this->command->insert($label);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        return $this->redirect()->toRoute(
                'label/detail',
                ['id' => $inserted_label->id]
        );
    }
    
    /**
     * Edit action
     * @return ViewModel
     */
    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('label');
        }
        
        try {
            $label = $this->repository->fetch($id);
        } catch (InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('label');
        }
        
        $this->form->bind($label);
        $view_model = new ViewModel(['form' => $this->form]);
        $request = $this->request;
        if (!$request->isPost()) {
            return $view_model;
        }
        
        $this->form->setData($request->getPost());
        if (!$this->form->isValid()) {
            return $view_model;
        }
        
        $updated_label = $this->command->update($label);
        return $this->redirect()->toRoute(
                'label/detail',
                ['id' => $updated_label->id]
        );
    }
}
