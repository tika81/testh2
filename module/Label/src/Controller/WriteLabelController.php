<?php
namespace Label\Controller;

use Zend\Form\FormInterface;
use Core\Model\CommandInterface;
use Core\Model\RepositoryInterface;
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
     * @var CommandInterface 
     */
    private $command;
    
    /**
     * @var FormInterface
     */
    private $form;
    
    /**
     * @var RepositoryInterface
     */
    private $repository;
    
    /**
     * Logger
     * @var LoggerInterface 
     */
    protected $logger;

    /**
     * @param CommandInterface $command
     * @param FormInterface $form
     * @param RepositoryInterface $repository
     * @param LoggerInterface $logger
     */
    public function __construct(
            CommandInterface $command, 
            FormInterface $form, 
            RepositoryInterface $repository,
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
            $this->logger->critical(sprintf(
                '[Line:%d] - %s File: %s', __LINE__, $ex->getMessage(), __FILE__
            ));
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
             $this->logger->error(sprintf(
                '[Line:%d] - Identifier \'id\' not found, file: %s', __LINE__, 
                    __FILE__
            ));
            return $this->redirect()->toRoute('label');
        }
        
        try {
            $label = $this->repository->fetch($id);
        } catch (InvalidArgumentException $ex) {
            $this->logger->error(sprintf(
                '[Line:%d] - %s File: %s', __LINE__, $ex->getMessage(), __FILE__
            ));
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
        
        try {
            $updated_label = $this->command->update($label);
        } catch (Exception $ex) {
            $this->logger->critical(sprintf(
                '[Line:%d] - %s File: %s', __LINE__, $ex->getMessage(), __FILE__
            ));
            throw $ex;
        }
        
        return $this->redirect()->toRoute(
                'label/detail',
                ['id' => $updated_label->id]
        );
    }
}
