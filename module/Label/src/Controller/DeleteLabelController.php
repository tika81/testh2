<?php
namespace Label\Controller;

use Label\Model\Label;
use Label\Model\LabelCommand;
use Label\Model\LabelRepository;
use InvalidArgumentException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
     * @param LabelCommand $command
     * @param LabelRepository $repository
     */
    public function __construct(
            LabelCommand $command, 
            LabelRepository $repository
    ) {
        $this->command = $command;
        $this->repository = $repository;
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

