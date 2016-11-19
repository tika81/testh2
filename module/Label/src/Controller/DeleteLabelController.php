<?php
namespace Label\Controller;

use Label\Model\LabelCommand;
use Label\Model\LabelRepository;
use InvalidArgumentException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Psr\Log\LoggerInterface;

/**
 * Delete Label Controller
 * @author bojan
 */
class DeleteLabelController extends AbstractActionController
{
    /**
     * @var LabelCommand 
     */
    private $command;
    
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
     * @param LabelRepository $repository
     * @param LoggerInterface $logger
     */
    public function __construct(
            LabelCommand $command, 
            LabelRepository $repository,
            LoggerInterface $logger
    ) {
        $this->command = $command;
        $this->repository = $repository;
        $this->logger = $logger;
    }
    
    /**
     * Delete Action
     * @return ViewModel
     */
    public function deleteAction()
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
        
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return new ViewModel(['label' => $label]);
        }
        
        if ($id != $request->getPost('id') || 'Delete' !== $request->getPost('confirm', 'no')) {
            return $this->redirect()->toRoute('label');
        }
        
        $deleted_label = $this->command->delete($label);
        return $this->redirect()->toRoute('label');
    }
    
}

